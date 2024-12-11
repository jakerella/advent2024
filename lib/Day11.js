// const input = '125 17'
const input = '965842 9159 3372473 311 0 6 86213 48'

let total1 = 0
let total2 = 0
let known = {}
const nums = input.split(' ').forEach((n) => {
    total1 += count(Number(n), 25)
    total2 += count(Number(n), 75)
})

// 1: 183435
// 2: 218279375708592
console.log(`Total 1: ${total1}`)
console.log(`Total 2: ${total2}`)

function count(n, steps) {
    if (known[`${n}-${steps}`]) {
        return known[`${n}-${steps}`]
    }

    if (steps === 1) {
        return (`${n}`.length % 2 === 0) ? 2 : 1
    }

    let c = 0
    if (n === 0) {
        c = count(1, steps-1)
    } else if (`${n}`.length % 2 === 0) {
        const chars = `${n}`.split('')
        c = count(Number(chars.slice(0, chars.length / 2).join('')), steps-1) + 
        count(Number(chars.slice(chars.length / 2).join('')), steps-1)
    } else {
        c = count(n * 2024, steps-1)
    }
    known[`${n}-${steps}`] = c
    return c
}
