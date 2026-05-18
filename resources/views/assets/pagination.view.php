<div class="d-flex justify-content-between align-items-center">
    <div>
        Mostrando de {{ $$paginate['from'] }} a {{ $$paginate['to'] }} de {{ $$paginate['total'] }} registros
    </div>
    <nav>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <li class="page-item">
                    <a class="page-link" href="{{ RUTA_URL . $$paginate['prev_page_url'] }}<?= isset($_GET['search']) ? "&search={$_GET['search']}" : "" ?>" aria-label="Previous">
                        <span aria-hidden="true">&lt;</span>
                    </a>
                </li>

                <?php for ($i = 1; $i <= $$paginate['last_page']; $i++): ?>
                    <li class="page-item">
                        <a class="page-link <?= $$paginate['current_page'] == $i ? 'active' : '' ?>" href="{{ RUTA_URL }}/{{ $paginate }}?page={{ $i }}<?= isset($_GET['search']) ? "&search={$_GET['search']}" : "" ?>" aria-current="page">{{ $i }}</a>
                    </li>
                <?php endfor ?>

                <li class="page-item">
                    <a class="page-link" href="{{ RUTA_URL . $$paginate['next_page_url'] }}<?= isset($_GET['search']) ? "&search={$_GET['search']}" : "" ?>" aria-label="Next">
                        <span aria-hidden="true">&gt;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </nav>
</div>