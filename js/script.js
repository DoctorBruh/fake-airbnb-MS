/*  js/script.js
 *  — handles the modal “View” button
 *  — fetches listing details via AJAX and fills the modal
 * ----------------------------------------------------------- */
$(function () {

    /* delegate click to all future .viewListing buttons */
    $(document).on('click', '.viewListing', function () {
        const id = $(this).data('id');

        $.get('src/ajax.php', { id })
            .done(function (data) {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                /* ---------- title + hero image ---------------- */
                $('#modalTitle').text(data.name);
                $('#modalImage')
                    .attr('src', data.pictureUrl)
                    .attr('alt', data.name);

                /* ---------- description (HTML allowed) -------- */
                $('#modalDescription').html(data.description);

                /* ---------- meta blurb stacked nicely --------- */
                $('#modalMeta').html(`
                  <div>${data.neighborhood} · ${data.roomType}</div>
                  <div>$${parseFloat(data.price).toFixed(2)} / night</div>
                  <div>Accommodates ${data.accommodates}</div>
                  <div><i class="bi bi-star-fill"></i> ${parseFloat(data.rating).toFixed(2)}</div>
                  <div>Hosted by ${data.hostName}</div>
                `);

                /* ---------- amenities in scroll box ----------- */
                $('#modalAmenities').text(data.amenities);
            })
            .fail(function () {
                alert('Could not load listing details – please try again.');
            });
    });

});
