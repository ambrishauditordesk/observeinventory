// Call the dataTables jQuery plugin
$(document).ready(function() {
    $('table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        select: true,
        scrollCollapse: true,
        responsive: true
    });
});