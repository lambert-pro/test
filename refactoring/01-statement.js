/**
 * 原始代码
 **/
function originStatement(invoice, plays) {
    let totalAmount = 0;
    let volumeCredits = 0;
    let result = `Statement for ${invoice.customer}\n`;
    const format = new Intl.NumberFormat("en-US",
        {
            style: "currency", currency: "USD",
            minimumFractionDigits: 2
        }).format;
    for (let perf of invoice.performances) {
        const play = plays[perf.playID];
        let thisAmount = 0;

        switch (play.type) {
            case "tragedy":
                thisAmount = 40000;
                if (perf.audience > 30) {
                    thisAmount += 1000 * (perf.audience - 30);
                }
                break;
            case "comedy":
                thisAmount = 30000;
                if (perf.audience > 20) {
                    thisAmount += 10000 + 500 * (perf.audience - 20);
                }
                thisAmount += 300 * perf.audience;
                break;
            default:
                throw new Error(`unknown type: ${play.type}`);
        }

        // add volume credits
        volumeCredits += Math.max(perf.audience - 30, 0);
        // add extra credit for every ten comedy attendees
        if ("comedy" === play.type) volumeCredits += Math.floor(perf.audience / 5);

        // print line for this order
        result += ` ${play.name}: ${format(thisAmount / 100)} (${perf.audience} seats)\n`;
        totalAmount += thisAmount;
    }
    result += `Amount owed is ${format(totalAmount / 100)}\n`;
    result += `You earned ${volumeCredits} credits\n`;
    return result;
}


/**
 * 重构后代码
 **/
function statement(invoice, plays) {
    let totalAmount = 0
    let volumeCredits = 0
    let result = `Statement for ${invoice.customer}\n`
    const format = new Intl.NumberFormat('en-US', {
        style: 'currency', currency: 'USD', minimumFractionDigits: 2
    }).format

    for (let perf of invoice.performances) {
        const play = plays[perf.playID]
        let thisAmount = amountFor(perf, play)

        // add volume credits
        volumeCredits += Math.max(perf.audience - 30, 0)
        // add extra credit for every ten comedy attendees
        if ('comedy' === play.type) volumeCredits += Math.floor(perf.audience / 5)

        // print line for this order
        result += ` ${play.name}: ${format(thisAmount / 100)} (${perf.audience} seats)\n`
        totalAmount += thisAmount
    }
    result += `Amount owed is ${format(totalAmount / 100)}\n`
    result += `You earned ${volumeCredits} credits\n`
    return result
}

// 计算费用
/** 首先，我需要检查一下，如果我将这块代码提炼到自己的一个函数里，有哪些变量会离开原本的作用域。
 * 在此示例中，是perf、play和thisAmount这3个变量。前两个变量会被提炼后的函数使用，但不会被修改，那么我就可以将它们以参数方式传递进来。
 * 我更关心那些会被修改的变量。这里只有唯一一个——thisAmount，因此可以将它从函数中直接返回。
 * 我还可以将其初始化放到提炼后的函数里。修改后的代码如下所示。
 * @param aPerformance 好代码应能清楚地表明它在做什么，而变量命名是代码清晰的关键。
 * @param play
 * @returns {number}
 */
function amountFor(aPerformance, play) {
    let result = 0

    switch (play.type) {
        case 'tragedy':
          result = 40000
            if (aPerformance.audience > 30) {
              result += 1000 * (aPerformance.audience - 30)
            }
            break
        case 'comedy':
          result = 30000
            if (aPerformance.audience > 20) {
              result += 10000 + 500 * (aPerformance.audience - 20)
            }
          result += 300 * aPerformance.audience
            break
        default:
            throw new Error(`unknown type: ${play.type}`)
    }
    return result
}

let plays = {
    'hamlet': {'name': 'Hamlet', 'type': 'tragedy'},
    'as-like': {'name': 'As You Like It', 'type': 'comedy'},
    'othello': {'name': 'Othello', 'type': 'tragedy'}
}

let invoice = {
    'customer': 'BigCo',
    'performances': [
        { 'playID': 'hamlet', 'audience': 55 },
        { 'playID': 'as-like', 'audience': 35 },
        { 'playID': 'othello', 'audience': 40 }
    ]
}

console.log('\n')
console.log(statement(invoice, plays))
console.log("重构技术就是以微小的步伐修改程序。如果你犯下错误，很容易便可发现它!")
