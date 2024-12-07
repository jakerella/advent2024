
const fs = require('fs');

const lines = fs.readFileSync('data/day07.txt').toString().split(/\n/)
let sum = 0
lines.forEach((line) => {
    let [target, nums] = line.split(': ')
    target = Number(target)
    nums = nums.split(' ').map(Number)
    if (addMultiplyOrConcat(target, nums)) {
        sum += target
    }
})

function addMultiplyOrConcat(target, nums) {
    if (nums.length === 1) {
        return (nums[0] === target)
    }
    if (addMultiplyOrConcat(target, [nums[0] * nums[1], ...nums.slice(2)])) {
        return true;
    }
    if (addMultiplyOrConcat(target, [nums[0] + nums[1], ...nums.slice(2)])) {
        return true;
    }
    concat = Number(`${nums[0]}${nums[1]}`)
    return addMultiplyOrConcat(target, [concat, ...nums.slice(2)]);
}

console.log(sum)
