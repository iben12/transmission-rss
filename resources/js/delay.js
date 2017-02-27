function delay(timeOffset) {
    let drop;
    this.wait = new Promise( (resolve,reject) => {
        drop = reject;
        setTimeout( () => {
            resolve();
        },timeOffset);
    });
    this.drop = drop;
};

module.exports = function(timeOffset) {
    return new delay(timeOffset);
}