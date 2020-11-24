var Speedometr = function (wordCountFunction, ending = 'w/s') {
    this.start_time = 0;
    this.end_time = 0;
    this.start_words = 0;
    this.end_words = 0;
    this.message_ending = ' ' + ending;
    this.last_message = '0' + this.message_ending;
    this.wordCountFunction = wordCountFunction;
}

Speedometr.prototype.getSpeed = function () {
    if (this.start_time === 0) {
        this.start_time = new Date();
        this.start_words = this.wordCountFunction();
    }
    this.end_time = new Date();
    this.end_words = this.wordCountFunction();

    if (this.start_words !== this.end_words) {
        var diff = (this.end_time.getTime() - this.start_time.getTime()) / 1000;
        this.start_time = this.end_time;
        this.start_words = this.end_words;

        this.last_message = Math.round(60 / diff) + this.message_ending;
    }

    return this.last_message;
}

export default Speedometr;
