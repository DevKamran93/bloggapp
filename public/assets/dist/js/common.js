// $(document).ready(function () {
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
// });
