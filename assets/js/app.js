/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';
import 'bootstrap';
import bsCustomFileInput from 'bs-custom-file-input';
import $ from 'jquery';

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

$(".toggle-sort").on('click', toggleSortButtons);
$("#scene_description_ajax").on('change', updateSceneDescription);
$("#scene_note_ajax").on('change', updateSceneNote);
initEditor();
