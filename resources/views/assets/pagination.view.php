<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        Mostrando de {{ $$paginate['from'] }} a {{ $$paginate['to'] }} de {{ $$paginate['total'] }} registros
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-end mb-0">
            
            <!-- Botón Anterior -->
            <li class="page-item {{ empty($$paginate['prev_page_url']) ? 'disabled' : '' }}">
                <a class="page-link" href="{{ !empty($$paginate['prev_page_url']) ? $$paginate['prev_page_url'] : '#' }}" aria-label="Previous">
                    <span aria-hidden="true">&lt;</span>
                </a>
            </li>

            <!-- Números de Página -->
            <?php 
            // Obtenemos la URL limpia del navegador y preparamos los query params existentes menos 'page'
            $currentUrlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $queryParams = $_GET;
            unset($queryParams['page']);
            $queryString = count($queryParams) > 0 ? '&' . http_build_query($queryParams) : '';
            
            for ($i = 1; $i <= $$paginate['last_page']; $i++): 
            ?>
                <li class="page-item {{ $$paginate['current_page'] == $i ? 'active' : '' }}">
                    <a class="page-link" href="<?= $currentUrlPath ?>?page=<?= $i ?><?= $queryString ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Botón Siguiente -->
            <li class="page-item {{ empty($$paginate['next_page_url']) ? 'disabled' : '' }}">
                <a class="page-link" href="{{ !empty($$paginate['next_page_url']) ? $$paginate['next_page_url'] : '#' }}" aria-label="Next">
                    <span aria-hidden="true">&gt;</span>
                </a>
            </li>
            
        </ul>
    </nav>
</div>
