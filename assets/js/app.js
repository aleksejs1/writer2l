import '../css/app.scss';
import 'bootstrap';
import bsCustomFileInput from 'bs-custom-file-input';
import $ from 'jquery';
import nameGen from './namegen';
import EditorStats from "./editorStats";

const config = {
    'baseUrl': '/',
    'autosavePeriod': 5500,
}

var customEditorContent = '';
var editorStats = null;

bsCustomFileInput.init();

function toggleSortButtons() {
    $(".sort-buttons").toggle();
    $(".sortable-menu").toggleClass("sortable-menu-active");
}

function patchField(field, value, api) {
    var data = {};
    data[field] = value;
    $.ajax({
        type: 'PATCH',
        data: JSON.stringify(data),
        contentType: 'application/merge-patch+json',
        url: config.baseUrl + api,
        success: function (data) {
            if ($('#c-saved').length) {
                var currentdate = new Date();
                var datetime = "Saved at "
                    + currentdate.toLocaleDateString()
                    + " @ "
                    + currentdate.toLocaleTimeString();
                $('#c-saved').html(datetime);
            }
        }
    });
}

function updateSceneDescription() {
    patchField(
        'description',
        $(this).val(),
        'api/scenes/' + $(this).data('scene')
    );
}

function updateSceneNote() {
    patchField(
        'note',
        $(this).val(),
        'api/scenes/' + $(this).data('scene')
    );
}

function initEditor() {
    if ($("#scene_content").length) {
        editorStats = new EditorStats();
        $("#scene_content").hide();
        $("<div id='custom-editor' contentEditable  class='form-control custom-editor'></div>").insertAfter("#scene_content");
        $("<div id='custom-editor-stats' class='btn btn-light float-right disabled'></div>").insertAfter("#scene_content");
        $("<div id='custom-editor-underline' class='btn btn-light'><u>U</u></div>").insertAfter("#scene_content");
        $("<div id='custom-editor-italic' class='btn btn-light'><i>I</i></div>").insertAfter("#scene_content");
        $("<div id='custom-editor-bold' class='btn btn-light'><b>B</b></div>").insertAfter("#scene_content");
        customEditorContent = $("#scene_content").val();
        $("#custom-editor").html($("#scene_content").val());
        $("#custom-editor-stats").html(editorStats.getStatSting());
        $("#custom-editor").on('keyup', function (e) {
            fixFirstParagraph();
            $("#scene_content").val($("#custom-editor").html());
            $("#custom-editor-stats").html(editorStats.getStatSting());
        });
        $(".scene-save").on('mousedown', function () {
            $("#scene_content").val($("#custom-editor").html());
            saveCustomEditor();
        });
        $('#custom-editor-bold').on('mousedown', function (e) {
            document.execCommand('bold', false, '');
            window.getSelection().getRangeAt(0).startContainer;
            $("#scene_content").val($("#custom-editor").html());
            e.preventDefault();
        });
        $('#custom-editor-italic').on('mousedown', function (e) {
            document.execCommand('italic', false, '');
            window.getSelection().getRangeAt(0).startContainer;
            $("#scene_content").val($("#custom-editor").html());
            e.preventDefault();
        });
        $('#custom-editor-underline').on('mousedown', function (e) {
            document.execCommand('underline', false, '');
            window.getSelection().getRangeAt(0).startContainer;
            $("#scene_content").val($("#custom-editor").html());
            e.preventDefault();
        });

        saveCustomEditor();
    }
}

function fixFirstParagraph() {
    var div = document.getElementById('custom-editor');

    if (div.firstChild !== null && div.firstChild.tagName === undefined) {
        var text = div.firstChild.textContent;
        div.removeChild(div.firstChild);
        var p = document.createElement('p');
        p.textContent = text;
        div.insertBefore(p, div.firstChild);

        var el = div.firstChild;
        el.focus();
        if (typeof window.getSelection != "undefined"
            && typeof document.createRange != "undefined") {
            var range = document.createRange();
            range.selectNodeContents(el);
            range.collapse(false);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        }
    }
}

function saveCustomEditor() {
    if (customEditorContent !== $("#custom-editor").html()) {
        customEditorContent = $("#custom-editor").html();
        patchField(
            'content',
            customEditorContent,
            'api/scenes/' + $("#scene_content").data('id')
        );
    }
    setTimeout(saveCustomEditor, config.autosavePeriod);
}

function initAvatarGenerator() {
    if (!$("#character_avatar").length) {
        return;
    }
    $("<div id='generate-avatar-btn' contentEditable  class='btn btn-light'>Generate</div>").insertAfter("#character_avatar");
    $("<select id='generate-avatar-sex'><option>male</option><option>female</option></select>").insertAfter("#character_avatar");
    if ($("#character_avatar").val()) {
        $(updateAvatar($("#character_avatar").val())).insertAfter("#character_avatar");
    }
    $('#generate-avatar-btn').on('mousedown', function (e) {
        var randStr = $("#generate-avatar-sex").val() + '_' + makeid(15);

        $(".auto-avatar").remove();
        $(updateAvatar(randStr)).insertAfter("#character_avatar");


        $("#character_avatar").val(randStr);
        // http://192.168.99.100/ru/avatar/200/female/asr123qr1on123or
    });

    $("#character_avatar").on('keydown', function () {
        $(".auto-avatar").remove();
        $(updateAvatar($("#character_avatar").val())).insertAfter("#character_avatar");
    })
}

function updateAvatar(avatar , size = 100) {
    var sex = 'male';
    console.log(avatar.substr(0,7));
    if (avatar.substr(0,5) === 'male_') {
        sex = 'male';
        avatar = avatar.substr(5, avatar.length);
    }
    if (avatar.substr(0,7) === 'female_') {
        sex = 'female';
        avatar = avatar.substr(7, avatar.length);
    }

    var avatarImg = "<img class='auto-avatar' src='" + config.baseUrl + 'en/avatar/' + size + '/' + sex + '/' + avatar + "'>";

    return avatarImg;
    // $(".auto-avatar").remove();
    // $("<img class='auto-avatar' src='" + config.baseUrl + 'en/avatar/100/' + sex + '/' + avatar + "'>").insertAfter("#character_avatar");
}

function initNameGenerator() {
    if (!$("#character_fullName").length) {
        return;
    }
    $("<div id='generate-name-btn' contentEditable  class='btn btn-light'>Generate</div>").insertAfter("#character_fullName");
    $("<select id='generate-name-sex'><option>male</option><option>female</option></select>").insertAfter("#character_fullName");
    $("<select id='generate-name-locale'><option>en</option><option>lv</option><option>ru</option></select>").insertAfter("#character_fullName");
    $('#generate-name-btn').on('mousedown', function (e) {
        $("#character_fullName").val(nameGen.generate($("#generate-name-sex").val(),$("#generate-name-locale").val()));
        e.preventDefault();
    });
}

function makeid(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function addChar() {
    var self = $(this);
    $.ajax({
        type: 'GET',
        data: {'add': $(this).data('char')},
        url: config.baseUrl + 'api/project/scene/'+$(this).data('id')+'/characters',
        success: function (data) {
            $("#char-in-list").append(self.parent().parent().parent());
            self.unbind();
            self.html('Remove');
            self.on('click', removeChar);
        }
    });
}
function removeChar() {
    var self = $(this);
    $.ajax({
        type: 'GET',
        data: {'remove': $(this).data('char')},
        url: config.baseUrl + 'api/project/scene/'+$(this).data('id')+'/characters',
        success: function (data) {
            $("#char-out-list").append(self.parent().parent().parent());
            self.unbind();
            self.html('Add');
            self.on('click', addChar);
        }
    });
}
function addLocation() {
    var self = $(this);
    $.ajax({
        type: 'GET',
        data: {'add': $(this).data('location')},
        url: config.baseUrl + 'api/project/scene/'+$(this).data('id')+'/locations',
        success: function (data) {
            $("#locations-in-list").append(self.parent().parent().parent());
            self.unbind();
            self.html('Remove');
            self.on('click', removeLocation);
        }
    });
}
function removeLocation() {
    var self = $(this);
    $.ajax({
        type: 'GET',
        data: {'remove': $(this).data('location')},
        url: config.baseUrl + 'api/project/scene/'+$(this).data('id')+'/locations',
        success: function (data) {
            $("#locations-out-list").append(self.parent().parent().parent());
            self.unbind();
            self.html('Add');
            self.on('click', addLocation);
        }
    });
}
function addItem() {
    var self = $(this);
    $.ajax({
        type: 'GET',
        data: {'add': $(this).data('item')},
        url: config.baseUrl + 'api/project/scene/'+$(this).data('id')+'/items',
        success: function (data) {
            $("#items-in-list").append(self.parent().parent().parent());
            self.unbind();
            self.html('Remove');
            self.on('click', removeItem);
        }
    });
}
function removeItem() {
    var self = $(this);
    $.ajax({
        type: 'GET',
        data: {'remove': $(this).data('item')},
        url: config.baseUrl + 'api/project/scene/'+$(this).data('id')+'/items',
        success: function (data) {
            $("#items-out-list").append(self.parent().parent().parent());
            self.unbind();
            self.html('Add');
            self.on('click', addItem);
        }
    });
}

$(".character-add").on('click', addChar);
$(".character-remove").on('click', removeChar);
$(".location-add").on('click', addLocation);
$(".location-remove").on('click', removeLocation);
$(".item-add").on('click', addItem);
$(".item-remove").on('click', removeItem);
$(".toggle-sort").on('click', toggleSortButtons);
$("#scene_description_ajax").on('change', updateSceneDescription);
$("#scene_note_ajax").on('change', updateSceneNote);
initEditor();
initNameGenerator();
initAvatarGenerator();


