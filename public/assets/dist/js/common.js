// document.addEventListener('DOMContentLoaded', function () {
//     const addUpdateForm = document.getElementById('add_update_category_form');
//     const addEditModal = document.getElementById('add_edit_category_modal');
//     const dataTable = $('#category_table').DataTable();

//     function openAddModal(modalId, modalTitle = '', btnText = '') {
//         const currentModal = document.getElementById(modalId);
//         currentModal.querySelector('#add_edit_modal_title').textContent = modalTitle;
//         currentModal.querySelector('#create_update_btn_text').textContent = btnText;
//         // Replace with actual method to show the modal
//         $(currentModal).modal('show');
//     }

//     function closeModal(modalId) {
//         const closeModal = document.getElementById(modalId);
//         const hiddenEvent = new Event('hidden.bs.modal');

//         closeModal.addEventListener('hidden.bs.modal', function () {
//             const form = closeModal.querySelector('form');
//             if (form) {
//                 form.reset();
//                 form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
//                 form.querySelectorAll('.invalid-feedback').forEach(element => element.textContent = '');
//             }
//         });

//         closeModal.dispatchEvent(hiddenEvent);
//         closeModal.classList.remove('show');
//         // Manually hide the modal overlay
//         document.body.classList.remove('modal-open');
//         const modalBackdrop = document.querySelector('.modal-backdrop');
//         if (modalBackdrop) {
//             modalBackdrop.parentNode.removeChild(modalBackdrop);
//         }
//     }


//     function saveCategoryData(event, button) {
//         event.preventDefault();
//         const $button = button;
//         $button.querySelector('#add_btn_spinner').classList.remove('d-none');
//         $button.disabled = true;
//         const url = $button.getAttribute('data-route');
//         const data = new FormData(addUpdateForm);
//         // Replace with actual method to send data to the server
//         SendAjaxRequestToServer('POST', url, data, '', createUpdateResponse);
//     }

//     function createUpdateResponse(response) {
//     if (response.status !== 200) {
//         // console.log(addUpdateForm);
//         addUpdateForm.querySelector('#type').classList.remove('is-invalid');
//         addUpdateForm.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
//         addUpdateForm.querySelectorAll('.invalid-feedback').forEach(element => element.textContent = '');

//         Object.entries(response.responseJSON.errors).forEach(([key, value]) => {
//             const errorElement = addUpdateForm.querySelector(`#${key}`);
//             const feedbackElement = errorElement.nextElementSibling;
//             errorElement.classList.add('is-invalid');
//             feedbackElement.classList.add('d-block');
//             feedbackElement.textContent = value[0];

//             if (key === 'type') {
//                 addUpdateForm.querySelector('#type').nextElementSibling.classList.add('d-block');
//                 addUpdateForm.querySelector('#type').nextElementSibling.textContent = value[0];
//             }
//         });

//         // Re-enable the button and hide the spinner
//         const $modal_save_button = document.getElementById('create_update_btn');
//         $modal_save_button.disabled = false;
//         $modal_save_button.querySelector('#add_btn_spinner').classList.add('d-none');
//         const $modal_close_button = document.getElementById('modal_close');
//     } else {
//         if (dataTable) {
//             dataTable.ajax.reload();
//         }
//         closeModal(addEditModal.getAttribute('id'));

//         const Toast = Swal.mixin({
//             toast: true,
//             position: 'top-right',
//             iconColor: 'white',
//             padding: '1em',
//             customClass: {
//                 popup: 'colored-toast',
//                 title: 'swal2-styled',
//             },
//             showConfirmButton: false,
//             timer: 4000,
//             timerProgressBar: true
//         });

//         Toast.fire({
//             icon: response.state,
//             title: response.message,
//         });
//     }
// }

// // });
// function fetchAllCategories(route = '') {
//         commonTable.DataTable({
//             "pagingType": 'numbers',
//             "orderable": true,
//             'pageLength': 10,
//             "lengthMenu": [
//                 [10, 15, 20, 25, 50, -1],
//                 [10, 15, 20, 25, 50, 'All'],
//             ],
//             "responsive": true,
//             "lengthChange": true,
//             "autoWidth": true,
//             "processing": true,
//             "serverSide": true,
//             "ajax": "{{ route('category.getAllCategoryData') }}",
//             "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
//             columns: [
//                 { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
//                 { data: 'category_title', name: 'category_title', orderable: true, searchable: true },
//                 { data: 'type', name: 'type', orderable: true, searchable: true },
//                 { data: 'user', name: 'user', orderable: false, searchable: false },
//                 { data: 'created_at', name: 'created_at', orderable: false, searchable: false },
//                 { data: 'updated_at', name: 'updated_at', orderable: false, searchable: false },
//                 { data: 'actions', name: 'actions', orderable: false, searchable: false },
//             ]
//         }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
//     }

$(document).ready(function () {
    // Spinner Hide/Show
    $("#save_update_btn").on("click", function () {
        var save_update_btn = $(this);
        save_update_btn.attr("disabled", true);
        save_update_btn.find("#save_update_btn_spinner").removeClass("d-none");
    });

    // For Restore/Delete
    var delete_restore_modal = $("#delete_restore_modal");

    $(document).on("click", ".delete_restore_data", function () {
        var action_btn = $(this);
        var delete_restore_modal_heading = delete_restore_modal.find(
            "#delete_restore_modal_heading"
        );
        var delete_restore_modal_btn = delete_restore_modal.find(
            "#delete_restore_modal_btn"
        );
        var delete_restore_modal_body = delete_restore_modal.find(
            "#delete_restore_modal_body"
        );

        if (action_btn.data("action") == "delete") {
            delete_restore_modal_heading
                .removeClass("bg-gradient-success")
                .addClass("bg-gradient-danger");
            delete_restore_modal_heading.children("h5").html("Delete ?");
            delete_restore_modal_body
                .children("h6")
                .html("Are You Sure, You Want To Delete ?");
            delete_restore_modal_btn
                .removeClass("bg-gradient-success")
                .addClass("bg-gradient-danger");
            delete_restore_modal_btn.find("#confirm_btn_text").text("Delete");
            delete_restore_modal_btn.attr(
                "data-action",
                action_btn.data("action")
            );
        } else {
            delete_restore_modal_heading
                .removeClass("bg-gradient-danger")
                .addClass("bg-gradient-success");
            delete_restore_modal_heading.children("h5").html("Restore ?");
            delete_restore_modal_body
                .children("h6")
                .html("Are You Sure, You Want To Restore ?");
            delete_restore_modal_btn
                .removeClass("bg-gradient-danger")
                .addClass("bg-gradient-success");
            delete_restore_modal_btn.find("#confirm_btn_text").text("Restore");
            delete_restore_modal_btn.attr(
                "data-action",
                action_btn.data("action")
            );
        }

        delete_restore_modal
            .find("#delete_restore_form #id")
            .val(action_btn.data("id"));
    });

    //     var delete_restore_modal = $("#delete_restore_modal");

    //     var image = localStorage.getItem("image");
    //     if (image) {
    //         $("#blog_image_preview").attr("src", image);
    //     }
    //     var _URL = window.URL || window.webkitURL;
    //     $("#blog_image").change(function (e) {
    //         var file, img;
    //         if ((file = this.files[0])) {
    //             img = new Image();
    //             var objectUrl = _URL.createObjectURL(file);
    //             img.onload = function () {
    //                 // if (this.width == 1200 && this.height == 630) {
    //                 $("#blog_image_preview").attr("src", objectUrl);
    //                 localStorage.setItem("image", objectUrl);
    //                 // } else {
    //                 //     $(function() {
    //                 //         var Toast = Swal.mixin({
    //                 //             toast: true,
    //                 //             position: 'top-end',
    //                 //             showConfirmButton: false,
    //                 //             timer: 10000
    //                 //         });

    //                 //         Toast.fire({
    //                 //             icon: 'error',
    //                 //             title: 'Image Diamension Should be 1200 x 630',
    //                 //             // background: 'gray',
    //                 //         })
    //                 //         $('#blog_image').val('');
    //                 //     });
    //                 //     _URL.revokeObjectURL(objectUrl);
    //                 // }
    //             };
    //             img.src = objectUrl;
    //         }
    //     });

    //     // Restore/Delete
    //     // $(document).on("click", ".delete_restore_category", function () {
    //     //     var action_btn = $(this);
    //     //     var route = action_btn.data("route");
    //     //     var delete_restore_modal_heading = delete_restore_modal.find(
    //     //         "#delete_restore_modal_heading"
    //     //     );
    //     //     var delete_restore_modal_btn = delete_restore_modal.find(
    //     //         "#delete_restore_modal_btn"
    //     //     );
    //     //     var delete_restore_modal_body = delete_restore_modal.find(
    //     //         "#delete_restore_modal_body"
    //     //     );

    //     //     if (action_btn.data("action") == "delete") {
    //     //         delete_restore_modal_heading
    //     //             .removeClass("bg-gradient-success")
    //     //             .addClass("bg-gradient-danger");
    //     //         delete_restore_modal_heading.children("h5").html("Delete ?");
    //     //         delete_restore_modal_body
    //     //             .children("h6")
    //     //             .html("Are You Sure, You Want To Delete ?");
    //     //         delete_restore_modal_btn
    //     //             .removeClass("bg-gradient-success")
    //     //             .addClass("bg-gradient-danger")
    //     //             .text("Delete");
    //     //         delete_restore_modal_btn.attr(
    //     //             "data-action",
    //     //             action_btn.data("action")
    //     //         );
    //     //     } else {
    //     //         delete_restore_modal_heading
    //     //             .removeClass("bg-gradient-danger")
    //     //             .addClass("bg-gradient-success");
    //     //         delete_restore_modal_heading.children("h5").html("Restore ?");
    //     //         delete_restore_modal_body
    //     //             .children("h6")
    //     //             .html("Are You Sure, You Want To Restore ?");
    //     //         delete_restore_modal_btn
    //     //             .removeClass("bg-gradient-danger")
    //     //             .addClass("bg-gradient-success")
    //     //             .text("Restore");
    //     //         delete_restore_modal_btn.attr(
    //     //             "data-action",
    //     //             action_btn.data("action")
    //     //         );
    //     //     }

    //     //     delete_restore_modal
    //     //         .find("#delete_restore_form #id")
    //     //         .val(action_btn.data("id"));
    //     // });

    //     // $(document).on("click", "#delete_restore_modal_btn", function (e) {
    //     //     let dalate_restore_form = $("#delete_restore_form");
    //     //     var action_btn = $(this);
    //     //     var url = "{{ route(" + route + ") }}";
    //     //     var data = new FormData(dalate_restore_form[0]);

    //     //     SendAjaxRequestToServer(
    //     //         "POST",
    //     //         url,
    //     //         data,
    //     //         "json",
    //     //         deleteRestoreResponse
    //     //     );
    //     // });

    //     // function deleteRestoreResponse(response) {
    //     //     if (response.status == 200) {
    //     //         table.DataTable().ajax.reload();
    //     //         $(function () {
    //     //             var Toast = Swal.mixin({
    //     //                 toast: true,
    //     //                 position: "top-end",
    //     //                 showConfirmButton: false,
    //     //                 timer: 5000,
    //     //             });

    //     //             Toast.fire({
    //     //                 icon: response.state,
    //     //                 title: response.message,
    //     //                 // background: 'gray',
    //     //             });

    //     //             // fetchAllCategories();
    //     //             // deleteRestoreModalReset();
    //     //         });
    //     //     }
    //     // }
});
