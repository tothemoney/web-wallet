"use strict";

const EXPLORER_TX_URL = 'https://tmyscan.com/tx/';
const EXPLORER_TOKEN_URL = 'https://tmyscan.com/token/';
const TX_HISTORY_URL = 'https://n1.tmychain.org/indexer?address=';
const TOKEN_HISTORY_URL = 'https://n1.tmychain.org/indexer?tokenhistory=';
const TOKEN_BALANCE_URL = 'https://n1.tmychain.org/indexer?tokenbalance=';
const ADDRESS_QR = 'https://n1.tmychain.org/indexer?qr=';
const WS = 'https://node1.tmyblockchain.org/ws';
const chaidId = 8768;
const rpc = {
    8768: 'https://node1.tmyblockchain.org/rpc',
};
const PRECISION = 9;
const defaultRPC = rpc[chaidId];
const chainData = {
    chainId: "0x" + parseInt(chaidId).toString(16),
    chainName: 'TMY Chain',
    nativeCurrency: {
        name: 'TMY Chain',
        symbol: 'TMY',
        decimals: 18
    },
    iconUrls: ['https://raw.githubusercontent.com/tothemoney/interface/TMY_redesign/src/assets/images/tmy.png'],
    rpcUrls: ['https://node1.tmyblockchain.org/rpc', 'https://node3.tmyblockchain.org/rpc'],
    blockExplorerUrls: ['https://tmyscan.com']
};
const ONPAGE_HISTORY = 100;

let activeChainId = '';
let logined = false;
/**
 * Example JavaScript code that interacts with the page and Web3 wallets
 */

// Unpkg imports
const Web3Modal = window.Web3Modal.default;
const WalletConnectProvider = window.WalletConnectProvider.default;
const Fortmatic = window.Fortmatic;

// Web3modal instance
let web3Modal

// Chosen wallet provider given by the dialog window
let provider;
let defaultWeb3;

// Address of the selected account
let selectedAccount;
let headersubs;
let logssubs;
let tmytokenlist;
let tokenHistory = {};
let tmyHistory = {};
/**
 * Setup the orchestra
 */
function init() {

    // Tell Web3modal what providers we have available.
    // Built-in web browser provider (only one can exist as a time)
    // like MetaMask, Brave or Opera is added automatically by Web3modal
    const providerOptions = {
        walletconnect: {
            package: WalletConnectProvider,
            options: {
                bridge: 'https://bridge.walletconnect.org',
                chainId: chaidId,
                // Mikko's test key - don't copy as your mileage may vary
                //infuraId: infuraId,
                rpc: rpc,
                network: "ethereum",
            }
        },
    };

    web3Modal = new Web3Modal({
        chainId: chaidId,
        cacheProvider: true, // optional
        providerOptions, // required
    });



    defaultWeb3 = new Web3(defaultRPC);
    loadinfo(defaultWeb3);

    if (web3Modal.cachedProvider) {
        onConnect();
    }

}

function showTokensPane() {
    $("#tokens").addClass('active');
    $("#common").removeClass('active');
    return false;
}

function showWalletPane() {
    $("#tokens").removeClass('active');
    $("#common").addClass('active');
    return false;
}

function getDate(timestamp) {
    let date = new Date(timestamp * 1000);
    let d = date.getDate(), m = (date.getMonth() + 1), y = date.getFullYear(), h = date.getHours(), i = date.getMinutes();

    if (d < 9) {
        d = "0" + d;
    }

    if (m < 9) {
        m = "0" + m;
    }

    if (h < 9) {
        h = "0" + h;
    }

    if (i < 9) {
        i = "0" + i;
    }

    return d +
        "/" + m +
        "/" + y +
        " " + h +
        ":" + m;
}


/**
 * Kick in the UI action after Web3modal dialog has chosen a provider
 */
async function fetchAccountData() {
    //preloader show

    // Get a Web3 instance for the wallet
    const web3 = new Web3(provider);
    activeChainId = await web3.eth.getChainId();
    const chainIdDec = activeChainId;

    if (!await checkChainId(false)) {
        console.log('disconnect?');
        await onDisconnect();
        return;
    }

    headersubs = web3.eth.subscribe('newBlockHeaders', (err, res) => {
        $("#connection_status").html("<i class='fa fa-circle online'></i>&nbsp; online");
        $("#BlockNumber").html(res.number);
    })

    // Get list of accounts of the connected wallet
    const accounts = await web3.eth.getAccounts();

    // MetaMask does not give you all accounts, only the selected account
    console.log("Got accounts", accounts);
    selectedAccount = accounts[0];

    if (selectedAccount)
        logined = true;


    let blockNumber = await web3.eth.getBlockNumber();
    let gasprice = await web3.eth.getGasPrice();

    $("#BlockNumber").html(blockNumber);
    $("#gasprice").html(parseFloat(web3.utils.fromWei(gasprice, 'ether')).toFixed(PRECISION));


    logssubs = web3.eth.subscribe('logs', {
        address: selectedAccount
    }, function (error, result) {
        console.log('logs subs: ', result)
    })
        .on("data", function (log) {
            console.log('received log for my addr: ', log)
            //console.log(log);
            web3.eth.getTransaction(log.transactionHash)
                .then(function (transaction) {
                    console.log('received tx for my addr: ', transaction)
                    //console.log(transaction)
                    if (transaction.value > 0) {
                        console.log("Ether sent from " + transaction.from + " to " + transaction.to)
                    }
                })
        })

    await loadinfo(web3);

}

async function loadinfo(web3) {

    if (logined) {
        $("#account").html(selectedAccount);
        $("#account_copy").val(selectedAccount);
        const balance = await web3.eth.getBalance(selectedAccount);

        // ethBalance is a BigNumber instance
        // https://github.com/indutny/bn.js/
        const ethBalance = web3.utils.fromWei(balance, "ether");
        const humanFriendlyBalance = parseFloat(ethBalance).toFixed(PRECISION);

        $('#balance').html(humanFriendlyBalance.toString() + " TMY");

        $.get(ADDRESS_QR + "" + selectedAccount, function (data, status) {
            $("#qr_image").attr('src', data.qr);
        });

        await loadTokenBalance(web3);
        loadHistory(web3);
        loadTokenHistory(web3);
        $("#send_error").addClass('hide');
        $("#send_success").addClass('hide');
    } else {
        $('#balance').html(parseFloat(0).toFixed(PRECISION).toString() + " TMY")
        $("#account").html('0x0000000000000000000000000000000000000000');
        $("#account_copy").val('0x0000000000000000000000000000000000000000');
        $("#send_error").addClass('hide');
        $("#send_success").addClass('hide');
        loadHistory(web3, false);
        loadTokenHistory(web3, false);
    }
}

function loadHistory(web3, unload) {
    if (unload) {
        //history unfill
        $("#history").html("<div class='text-center'>" + window.t.noRecords + "</div>");
        $("#history_pager").html("");
        return false;
    }

    if (selectedAccount)
        $.get(TX_HISTORY_URL + "" + selectedAccount, function (data, status) {
            console.log(data);

            tmyHistory = {};
            let page = 0;
            for (let row of data) {

                let r = tmyHistory[page];
                if (!r)
                    r = [];

                if (r.length >= ONPAGE_HISTORY) {
                    page++;
                    r = tmyHistory[page];

                    if (!r)
                        r = [];
                }


                r.push(row);
                tmyHistory[page] = r;
            }

            window.showTmyHistoryPage(1);

            /*
            amount: "10000000000000000000"
            blockNumber: 37732
            hash: "0x456ff8d3515af78b35bb381d779982bf23173e61219bc2f94f6c2abc13233fcb"
            source: "0x14CB17E7038B7Cc92804bFb13Da7728161c26e0A"
            timestamp: 1650818705
            type: "income"
            */

        });
}

window.showTmyHistoryPage = function (page) {
    let data = tmyHistory[page - 1];
    if (!data)
        data = [];

    let header = $(".tableEl-header-template").clone();
    header.removeClass('hide').removeClass('tableEl-header-template');

    $("#history").html("");
    $("#history").html(header);

    if (!data.length) {
        $("#history").append('<div class="tableEl-row text-center"><div class="tableEl-row-cell"></div><div class="tableEl-row-cell text-xl-right text-lg-right text-md-center text-sm-center">Записи не найдены</div><div class="tableEl-row-cell"></div><div class="tableEl-row-cell"></div><div class="tableEl-row-cell"></div></div>')
        return;
    }

    for (let row of data) {
        let div = $(".history-template").clone();
        if (row.type == 'income') {
            div.find(".history-type").html("IN").addClass('taType-In');
            div.find(".sumCol").addClass('sum-growth');
        } else {
            div.find(".history-type").html("OUT").addClass('taType-Out');
            div.find(".sumCol").addClass('sum-loss');
        }

        div.removeClass('hide').removeClass('history-template');

        div.find(".history-item-date a").attr('href', EXPLORER_TX_URL + row.hash).html(getDate(row.timestamp));
        div.find(".history-item-amount").html((row.type == 'income' ? '+' : '-') + parseFloat(parseFloat(row.amount) / 1e18).toFixed(PRECISION));

        let addr = row.type == 'income' ? row.source : row.destination;
        let s = addr.slice(0, 12) + "...";

        if (tmytokenlist[addr]) {
            s = (row.type == 'income' ? 'from' : 'to') + ' <a target="_blank" href="' + EXPLORER_TOKEN_URL + addr + '">' + tmytokenlist[addr].name + ' (' + tmytokenlist[addr].symbol + ')</a>';
        }

        div.find(".history-item-address .addr-cut").html(s);
        div.find(".history-item-address .addr-full").html(addr);

        let fee = 0;
        if (row.type == 'outcome')
            fee = row.gas * parseFloat(row.gasPrice.toString()) / 1e18;
        div.find(".history-item-fee").html(parseFloat(fee).toFixed(PRECISION));
        $("#history").append(div);
    }


    //render pages
    let pages = Object.keys(tmyHistory).length;

    let str = "";

    if (page > 1 && pages > 3) {
        str += "<li><a onclick='return showTmyHistoryPage(" + (page - 1) + ");' href='#'>" + window.t.prevpage + "</a></li>";
    }

    if (pages > 1)
        for (let i = (page - 3 > 0 ? page - 3 : 1); i < page; i++) {
            str += "<li><a onclick='return showTmyHistoryPage(" + (i) + ");' href='#'>" + i + "</a></li>";
        }


    str += "<li class='acitvePage'><a href='#'>" + page + "</a></li>"

    if (pages > 1)
        for (let i = page + 1; i <= (page + 3 < pages ? page + 3 : pages); i++) {
            str += "<li><a onclick='return showTmyHistoryPage(" + i + ");' href='#'>" + i + "</a></li>";
        }

    if (page < pages && pages > 3) {
        str += "<li><a onclick='return showTmyHistoryPage(" + (page + 1) + ");' href='#'>" + window.t.prevpage + "</a></li>";
    }

    $("#history_pager").html(str);

    return false;
}

function loadTokenBalance(web3, unload) {

    return new Promise((resolve) => {
        if (selectedAccount)
            $.get(TOKEN_BALANCE_URL + "" + selectedAccount, function (data, status) {
                console.log(data);

                tmytokenlist = data;
                $("#tokenlist").html("");

                let select = document.querySelector("#sendtoken_list");
                select.innerHTML = '<option value="-1">' + window.t.selecttoken + '</option>';
                for (let addr in data) {
                    let balance = parseFloat(web3.utils.fromWei(data[addr].balanceOf, 'ether'));
                    console.log(data[addr].symbol, balance, data[addr].balanceOf, web3.utils.fromWei(data[addr].balanceOf, 'ether'));

                    var opt = document.createElement('option');
                    opt.value = addr;
                    opt.innerHTML = data[addr].name + " (" + data[addr].symbol + ") " + addr.slice(0, 8) + "...";
                    select.appendChild(opt);

                    if (balance <= 0)
                        continue;

                    $("#tokenlist").append("<div class='balance row'><div class='col-6 text-right'><a target='_blank' href='" + EXPLORER_TOKEN_URL + addr + "'>" + data[addr].name + "</a></div><div class='col-6'>" + balance.toFixed(18) + "&nbsp;" + data[addr].symbol + "</div></div>");
                }

                resolve();
            });
    })
}

function loadTokenHistory(web3, unload) {
    if (unload) {
        //history unfill
        $("#tokenhistory").html("<div class='text-center'>" + window.t.noRecords + "</div>");
        $("#tokenhistory_pager").html("");
        return false;
    }

    if (selectedAccount)
        $.get(TOKEN_HISTORY_URL + "" + selectedAccount, function (data, status) {
            console.log(data);

            tokenHistory = {};
            let page = 0;
            for (let row of data) {

                let r = tokenHistory[page];
                if (!r)
                    r = [];

                if (r.length >= ONPAGE_HISTORY) {
                    page++;
                    r = tokenHistory[page];

                    if (!r)
                        r = [];
                }


                r.push(row);
                tokenHistory[page] = r;
                //

            }


            window.showTokenHistoryPage(1);
        });
}

window.showTokenHistoryPage = function (page) {
    let data = tokenHistory[page - 1];
    if (!data)
        data = [];

    let header = $(".tokenhistory-header-template").clone();
    header.removeClass('hide').removeClass('tokenhistory-header-template');

    $("#tokenhistory").html("");
    $("#tokenhistory").html(header);

    if (!data.length) {
        $("#tokenhistory").append('<div class="tableEl-row text-center"><div class="tableEl-row-cell"></div><div class="tableEl-row-cell text-xl-right text-lg-right text-md-center text-sm-center">Записи не найдены</div><div class="tableEl-row-cell"></div><div class="tableEl-row-cell"></div><div class="tableEl-row-cell"></div></div>')
        return;
    }


    for (let row of data) {
        let div = $(".historytoken-template").clone();
        if (row.type == 'income')
            div.addClass('history-in');
        else
            div.addClass('history-out');

        div.removeClass('hide').removeClass('historytoken-template');


        div.find(".history-item-date a").attr('href', EXPLORER_TX_URL + row.hash).html(getDate(row.timestamp));
        div.find(".history-item-amount").html((row.type == 'income' ? '+' : '-') + (parseFloat(row.amount) / 1e18).toFixed(PRECISION) + " <a target='_blank' href='" + EXPLORER_TOKEN_URL + "" + row.contractAddress + "'>" + tmytokenlist[row.contractAddress].symbol + "</a>");
        div.find(".history-item-source .addr-cut").html((row.type == 'income' ? row.source : selectedAccount).slice(0, 12) + "...");
        div.find(".history-item-destination .addr-cut").html((row.type == 'income' ? selectedAccount : row.destination).slice(0, 12) + "...");

        div.find(".history-item-source .addr-full").html((row.type == 'income' ? row.source : selectedAccount));
        div.find(".history-item-destination .addr-full").html((row.type == 'income' ? selectedAccount : row.destination));


        let fee = 0;
        if (row.type == 'outcome')
            fee = row.gas * (parseFloat(row.gasPrice.toString()) / 1e18);
        div.find(".history-item-fee").html(parseFloat(fee).toFixed(PRECISION));
        $("#tokenhistory").append(div);
    }


    //render pages
    let pages = Object.keys(tokenHistory).length;

    let str = "";

    if (page > 1 && pages > 3) {
        str += "<li><a onclick='return showTokenHistoryPage(" + (page - 1) + ");' href='#'>" + window.t.prevpage + "</a></li>";
    }

    if (pages > 1)
        for (let i = (page - 3 > 0 ? page - 3 : 1); i < page; i++) {
            str += "<li><a onclick='return showTokenHistoryPage(" + (i) + ");' href='#'>" + i + "</a></li>";
        }


    str += "<li class='acitvePage'><a href='#'>" + page + "</a></li>"

    if (pages > 1)
        for (let i = page + 1; i <= (page + 3 < pages ? page + 3 : pages); i++) {
            str += "<li><a onclick='return showTokenHistoryPage(" + i + ");' href='#'>" + i + "</a></li>";
        }

    if (page < pages && pages > 3) {
        str += "<li><a onclick='return showTokenHistoryPage(" + (page + 1) + ");' href='#'>" + window.t.nextpage + "</a></li>";
    }

    $("#tokenhistory_pager").html(str);

    return false;
}

/**
 * Fetch account data for UI when
 * - User switches accounts in wallet
 * - User switches networks in wallet
 * - User connects wallet initially
 */
async function refreshAccountData() {


    // Disable button while UI is loading.
    // fetchAccountData() will take a while as it communicates
    // with Ethereum node via JSON-RPC and loads chain data
    // over an API call.
    $("#preloader").removeClass('hide')
    await fetchAccountData(provider);
    $("#preloader").addClass('hide')
}

async function checkChainId(load) {

    console.log('logined', logined, 'activechainid', activeChainId, 'chainId', chaidId);
    const web3 = new Web3(provider);

    if (load)
        activeChainId = await web3.eth.getChainId();

    if (activeChainId != chaidId) {
        if (confirm(window.t.mustbe + ' ' + chainData.chainName + ' ' + window.t.mustbefirst)) {
            try {
                await web3.currentProvider.request({
                    method: 'wallet_addEthereumChain',
                    params: [chainData]
                });

                await web3.currentProvider.request({
                    method: "wallet_switchEthereumChain",
                    params: [{ chainId: web3.utils.toHex(chaidId) }]
                });

                return true;
            } catch (e) {
                console.log('error', e)
                return false;
            }

        }
        return false;
    }

    return true;
}

/**
 * Connect wallet button pressed.
 */
async function onConnect() {

    console.log("Opening a dialog", web3Modal);
    try {
        provider = await web3Modal.connect();

        $("#connect").addClass('hide')
        $("#connect-menu").addClass('hide')
        $("#disconnect").removeClass('hide');
        $(".access").addClass('hide');
        $(".wallet").removeClass('hide');
        $("#connection_status").html("<i class='fa fa-circle online'></i>&nbsp; online");

        await web3.currentProvider.request({
            method: 'wallet_addEthereumChain',
            params: [chainData]
        });
    } catch (e) {
        console.log("Could not get a wallet connection", e);
        return;
    }

    // Subscribe to accounts change
    provider.on("accountsChanged", (accounts) => {
        fetchAccountData();
    });

    // Subscribe to chainId change
    provider.on("chainChanged", async (chainId) => {
        activeChainId = chainId;
        if (await checkChainId(false)) {
            fetchAccountData();
        }
    });

    await refreshAccountData();
}

/**
 * Disconnect wallet button pressed.
 */
async function onDisconnect() {

    console.log("Killing the wallet connection", provider);
    logined = false;
    activeChainId = '';

    if (headersubs)
        headersubs.unsubscribe(function (error, success) {
            if (success) {
                $("#connection_status").html("<i class='fa fa-circle offline'></i>&nbsp; offline");
                console.log('Successfully unsubscribed!');
            }
        });

    if (logssubs)
        logssubs.unsubscribe(function (error, success) {

        });


    if (web3Modal && web3Modal.clearCachedProvider)
        await web3Modal.clearCachedProvider();

    // TODO: Which providers have close method?
    if (provider.removeAllListeners)
        provider.removeAllListeners();

    if (provider.close) {
        await provider.close();
        // If the cached provider is not cleared,
        // WalletConnect will default to the existing session
        // and does not allow to re-scan the QR code with a new wallet.
        // Depending on your use case you may want or want not his behavir.
        provider = null;
    }

    selectedAccount = null;

    // Set the UI back to the initial state

    $("#disconnect").addClass('hide')
    $("#connect").removeClass('hide')
    $("#connect-menu").removeClass('hide')
    $(".access").removeClass('hide');
    $(".wallet").addClass('hide');
}

async function sendTMY() {

    const web3 = new Web3(provider);
    $("#send_error").addClass('hide').html("");
    let address = $("#send_address").val();
    if (!web3.utils.isAddress(address)) {
        $("#send_error").removeClass('hide').html(window.t.addressinvalid);
        return false;
    }

    let amount = parseFloat($("#send_amount").val());
    const balance = await web3.eth.getBalance(selectedAccount);
    const ethBalance = web3.utils.fromWei(balance, "ether");

    if (amount <= 0 || amount > ethBalance) {
        $("#send_error").removeClass('hide').html(window.t.send_balance_error + ": " + parseFloat(ethBalance).toFixed(PRECISION));
        return false;
    }

    $("#send_error").addClass('hide').html("");
    web3.eth.sendTransaction({
        from: selectedAccount,
        to: address,
        value: web3.utils.toWei(amount.toString(), 'ether'),
        gas: 21000
    })
        .on('transactionHash', function (hash) {
            $("#send_success").removeClass('hide').html(window.t.send_success + ", hash: <a target='_blank' href='" + EXPLORER_TX_URL + hash + "'>" + hash + "</a>");
            document.querySelector("#sendtmy").reset()
        })
        .on('confirmation', function (confirmationNumber, receipt) {
            if (confirmationNumber == 0) {
                loadHistory(web3);
            }

            if (confirmationNumber == 5) {
                $("#send_success").addClass('hide').html("");
                $("#send_error").addClass('hide').html("");
            }
        })
        .then(reciept => {
            if (!reciept.status)
                $("#send_error").removeClass('hide').html(window.t.tx + " " + reciept.transactionHash + " " + window.t.txrejected);
            document.querySelector("#sendtmy").reset()
        })
        .catch(e => {
            $("#send_error").removeClass('hide').html(e.message);
        })

}

async function sendToken() {

    const web3 = new Web3(provider);
    $("#send_token_error").addClass('hide').html("");
    let address = $("#sendtoken_address").val();
    if (!web3.utils.isAddress(address)) {
        $("#send_token_error").removeClass('hide').html(window.t.addressinvalid);
        return false;
    }

    let contractAddress = $("#sendtoken_list").val();

    if (!contractAddress || contractAddress == -1) {
        $("#send_token_error").removeClass('hide').html(window.t.choosetokenerror);
        return false;
    }

    if (!web3.utils.isAddress(contractAddress)) {
        $("#send_token_error").removeClass('hide').html(window.t.invalidtokenaddress);
        return false;
    }

    let amount = parseFloat($("#sendtoken_amount").val());
    if (isNaN(amount))
        amount = 0;

    const balance = tmytokenlist[contractAddress].balance;
    const tokenBalance = web3.utils.fromWei(balance, "ether");

    if (amount <= 0 || amount > tokenBalance) {
        $("#send_token_error").removeClass('hide').html(window.t.send_balance_error + ": " + parseFloat(tokenBalance).toFixed(PRECISION) + " " + tmytokenlist[contractAddress].symbol);
        return false;
    }

    $("#send_token_error").addClass('hide').html("");

    const tokenContract = new web3.eth.Contract([
        {
            "type": "function",
            "stateMutability": "nonpayable",
            "outputs": [
                {
                    "type": "bool",
                    "name": "",
                    "internalType": "bool"
                }
            ],
            "name": "transfer",
            "inputs": [
                {
                    "type": "address",
                    "name": "to",
                    "internalType": "address"
                },
                {
                    "type": "uint256",
                    "name": "amount",
                    "internalType": "uint256"
                }
            ]
        },
    ], contractAddress);

    tokenContract.methods.transfer(address, web3.utils.toWei(amount.toString(), 'ether')).send({ from: selectedAccount })
        .on('transactionHash', function (hash) {
            $("#send_token_success").removeClass('hide').html(window.t.send_success + ", hash: <a target='_blank' href='" + EXPLORER_TX_URL + hash + "'>" + hash + "</a>");
            document.querySelector("#sendtmy_tokens").reset()
        })
        .on('confirmation', function (confirmationNumber, receipt) {
            if (confirmationNumber == 4) {
                loadTokenBalance(web3);
                loadTokenHistory(web3);
            }

            if (confirmationNumber == 6) {
                $("#send_token_success").addClass('hide').html("");
                $("#send_token_error").addClass('hide').html("");
            }
        })
        .then(reciept => {
            if (!reciept.status)
                $("#send_token_error").removeClass('hide').html(window.t.tx + " " + reciept.transactionHash + " " + window.t.txrejected);
            document.querySelector("#sendtmy_tokens").reset()
        })
        .catch(e => {
            $("#send_token_error").removeClass('hide').html(e.message);
        })
}

function copyAddress() {
    copyTextToClipboard(selectedAccount)
}

/**
 * Main entry point.
 */
window.addEventListener('load', async () => {
    init();
    document.querySelector("#connect-menu").addEventListener("click", onConnect);
    document.querySelector("#connect").addEventListener("click", onConnect);
    document.querySelector("#disconnect").addEventListener("click", onDisconnect);
    document.querySelector('#copyto').addEventListener('click', copyAddress)
    document.querySelector('#sendtmy_btn').addEventListener('click', sendTMY)
    document.querySelector("#sendtokens").addEventListener('click', sendToken);
});