<?php /* Generic public page: renders the page's blocks in order. */
if (!empty($edit)) {
    echo blocks_render_editable($blocks, (int) $page['id']);
} else {
    echo blocks_render_all($blocks);
}
