@php
    /** @var bool $includeJquery */
    $includeJquery = $includeJquery ?? false;
@endphp

{{-- DataTables (Bootstrap 5 + Responsive) --}}
@push('vendor-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" crossorigin="anonymous">
@endpush

@push('vendor-js')
    @if ($includeJquery)
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    @endif
    <script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>
@endpush

@push('scripts')
    <script>
        (function () {
            if (typeof jQuery === 'undefined' || !jQuery.fn || !jQuery.fn.DataTable) return;

            function boolAttr(el, name, defaultVal) {
                const v = el.getAttribute(name);
                if (v === null || v === '') return defaultVal;
                return !(v === '0' || v === 'false');
            }

            jQuery(function () {
                jQuery('table.datatable').each(function () {
                    const table = this;
                    const $t = jQuery(table);
                    if (!$t.find('tbody tr').length) return;
                    if (jQuery.fn.dataTable.isDataTable(table)) return;

                    const pageLength = parseInt(table.getAttribute('data-page-length') || '10', 10);
                    const ordering = boolAttr(table, 'data-ordering', true);
                    const searching = boolAttr(table, 'data-searching', true);
                    const paging = boolAttr(table, 'data-paging', true);
                    const responsive = boolAttr(table, 'data-responsive', true);
                    const autoWidth = boolAttr(table, 'data-autowidth', false);
                    const disableLastCol = boolAttr(table, 'data-disable-last-sort', true);

                    $t.DataTable({
                        pageLength: isNaN(pageLength) ? 10 : pageLength,
                        responsive: responsive,
                        autoWidth: autoWidth,
                        ordering: ordering,
                        searching: searching,
                        paging: paging,
                        columnDefs: disableLastCol ? [{ orderable: false, searchable: false, targets: -1 }] : [],
                        language: {
                            search: "",
                            searchPlaceholder: "Search…",
                            lengthMenu: "Show _MENU_",
                            info: "Showing _START_ to _END_ of _TOTAL_",
                            paginate: { next: "→", previous: "←" }
                        },
                        dom:
                            "<'flex flex-wrap items-center justify-between gap-4 mb-4'<'flex items-center text-xs'l><'flex items-center'f>>" +
                            "<'table-responsive'tr>" +
                            "<'flex flex-wrap items-center justify-between gap-4 mt-4'<'flex items-center text-xs text-gray-500'i><'flex items-center'p>>",
                    });

                    // Normalize filter/length controls to match theme
                    try {
                        const $wrap = $t.closest('.dataTables_wrapper');
                        $wrap.find('.dataTables_filter input')
                            .addClass('form-control')
                            .addClass('text-sm');
                        $wrap.find('.dataTables_length select')
                            .addClass('form-select')
                            .addClass('text-sm');
                    } catch (e) {}
                });
            });
        })();
    </script>
@endpush

