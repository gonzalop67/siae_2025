<div class="d-flex justify-content-between align-items-center">
    <div>
        Mostrando de {{ $$paginate['from'] }} a {{ $$paginate['to'] }} de {{ $$paginate['total'] }} registros
    </div>
    <nav>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <!-- Botón Anterior -->
                <li class="page-item {{ empty($$paginate['prev_page_url']) ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ !empty($$paginate['prev_page_url']) ? RUTA_URL . $$paginate['prev_page_url'] . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') : '#' }}" aria-label="Previous">
                        <span aria-hidden="true">&lt;</span>
                    </a>
                </li>

                <?php 
                // Detectamos automáticamente la URL actual del navegador limpia de parámetros (?page=)
                $currentUrlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                
                for ($i = 1; $i <= $$paginate['last_page']; $i++): 
                ?>
                    <li class="page-item {{ $$paginate['current_page'] == $i ? 'active' : '' }}">
                        <!-- CORRECCIÓN CLAVE: Usamos $currentUrlPath en lugar de /{{ $paginate }} -->
                        <a class="page-link" href="<?= $currentUrlPath ?>?page={{ $i }}<?= isset($_GET['search']) ? "&search=" . urlencode($_GET['search']) : "" ?>" aria-current="page">{{ $i }}</a>
                    </li>
                <?php endfor ?>

                <!-- Botón Siguiente -->
                <li class="page-item {{ empty($$paginate['next_page_url']) ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ !empty($$paginate['next_page_url']) ? RUTA_URL . $$paginate['next_page_url'] . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') : '#' }}" aria-label="Next">
                        <span aria-hidden="true">&gt;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </nav>
</div>
