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

$(".toggle-sort").on('click', toggleSortButtons);
$("#scene_description_ajax").on('change', updateSceneDescription);
$("#scene_note_ajax").on('change', updateSceneNote);