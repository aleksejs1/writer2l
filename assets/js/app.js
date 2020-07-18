import '../css/app.scss';
import 'bootstrap';
import bsCustomFileInput from 'bs-custom-file-input';
import $ from 'jquery';
import nameGen from './namegen';

const config = {
    'baseUrl': '/'
}

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
        $("#scene_content").hide();
        $("<div id='custom-editor' contentEditable  class='form-control custom-editor'></div>").insertAfter("#scene_content");
        $("<div id='custom-editor-underline' class='btn btn-light'><u>U</u></div>").insertAfter("#scene_content");
        $("<div id='custom-editor-italic' class='btn btn-light'><i>I</i></div>").insertAfter("#scene_content");
        $("<div id='custom-editor-bold' class='btn btn-light'><b>B</b></div>").insertAfter("#scene_content");
        $("#custom-editor").html($("#scene_content").val());
        document.execCommand("defaultParagraphSeparator", false, "p");
        $("#custom-editor").on('keyup', function (e) {
            $("#scene_content").val($("#custom-editor").html());
        });
        $('#custom-editor-bold').on('mousedown', function (e) {
            document.execCommand('bold', false, '');
            window.getSelection().getRangeAt(0).startContainer;
            e.preventDefault();
        });
        $('#custom-editor-italic').on('mousedown', function (e) {
            document.execCommand('italic', false, '');
            window.getSelection().getRangeAt(0).startContainer;
            e.preventDefault();
        });
        $('#custom-editor-underline').on('mousedown', function (e) {
            document.execCommand('underline', false, '');
            window.getSelection().getRangeAt(0).startContainer;
            e.preventDefault();
        });
    }
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

$(".toggle-sort").on('click', toggleSortButtons);
$("#scene_description_ajax").on('change', updateSceneDescription);
$("#scene_note_ajax").on('change', updateSceneNote);
initEditor();
initNameGenerator();



