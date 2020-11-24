import $ from "jquery";
import Speedometr from "./speedometr";

var EditorStats = function () {
    this.w = $('#trans-words').html() !== undefined ? $('#trans-words').html() : 'W';
    this.l = $('#trans-letters').html() !== undefined ? $('#trans-letters').html() : 'L';
    this.wpm = $('#trans-speed').html() !== undefined ? $('#trans-speed').html() : 'wpm';
    this.words = $('#trans-words').attr('title') !== undefined ? $('#trans-words').attr('title') : 'Words';
    this.letters = $('#trans-letters').attr('title') !== undefined ? $('#trans-letters').attr('title') : 'Letters';
    this.speed = $('#trans-speed').attr('title') !== undefined ? $('#trans-speed').attr('title') : 'Words per minute';
    this.speedometr = new Speedometr(this.wordCount, this.wpm);
}

EditorStats.prototype.getStatSting = function () {
    return ' <span title="' + this.speed + '">' + this.speedometr.getSpeed() + '</span>'
        + ' <span title="' + this.words + '">' + this.w + ':' + this.wordCount() + '</span>'
        + ' <span title="' + this.letters + '">' + this.l + ':' + this.charCount() + '</span>'
    ;
}

EditorStats.prototype.wordCount = function () {
    var elements = $("#custom-editor").children();
    var count = 0;
    for (var i = 0; i < elements.length; i++) {
        if (elements[i].textContent && elements[i].textContent.trim() !== "") {
            count += elements[i].textContent.trim().replace(/\s\s+/g, ' ').split(/\s/).length;
        }
    }
    return count;
}
EditorStats.prototype.charCount = function () {
    var elements = $("#custom-editor").children();
    var count = 0;
    for (var i = 0; i < elements.length; i++) {
        count += elements[i].textContent.length;
    }
    return count;
}


export default EditorStats;
