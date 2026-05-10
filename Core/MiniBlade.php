<?php namespace Core;

class MiniBlade {
    protected array $sections = [];
    protected ?string $layout = null;
    protected string $viewsPath;
    protected string $cachePath;
    protected bool $useCache = true; // Activado por defecto para performance

    public function __construct(string $viewsPath, string $cachePath, bool $useCache = true) {
        $this->viewsPath = rtrim($viewsPath, '/') . '/';
        $this->cachePath = rtrim($cachePath, '/') . '/';
        $this->useCache = $useCache;
        
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, true);
        }
    }

    public function render(string $viewName, array $data = []) {
        $this->layout = null; // Reset layout
        $content = $this->renderView($viewName, $data);

        // Si la vista definió un layout, lo renderizamos y metemos el contenido
        if ($this->layout) {
            return $this->renderView($this->layout, $data);
        }

        return $content;
    }

    protected function renderView(string $viewName, array $data) {
        $path = $this->viewsPath . str_replace('.', '/', $viewName) . ".view.php";
        
        if (!file_exists($path)) {
            return "<!-- Vista [$viewName] no encontrada -->";
        }

        $cacheFile = $this->cachePath . md5($viewName) . '.php';

        // Solo compilamos si es necesario
        if (!$this->useCache || !file_exists($cacheFile) || filemtime($path) > filemtime($cacheFile)) {
            file_put_contents($cacheFile, $this->compile(file_get_contents($path)));
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include($cacheFile);
        return ob_get_clean();
    }

    protected function compile(string $code): string {
        $patterns = [
            '/@php(.*?)@endphp/s'       => '<?php $1 ?>',
            '/\{\{\s*(.*?)\s*\}\}/s'    => '<?php echo htmlspecialchars((string)($1), ENT_QUOTES, "UTF-8"); ?>',
            '/@if\s*\((.*?)\)/s'        => '<?php if($1): ?>',
            '/@else/'                   => '<?php else: ?>',
            '/@endif/'                  => '<?php endif; ?>',
            '/@foreach\s*\((.*?)\)/s'   => '<?php foreach($1): ?>',
            '/@endforeach/'             => '<?php endforeach; ?>',
            '/@include\(\'(.*?)\'\)/'   => '<?php echo $this->renderView(\'$1\', get_defined_vars()); ?>',
            '/@yield\(\'(.*?)\'\)/'     => '<?php echo $this->sections[\'$1\'] ?? ""; ?>',
        ];

        $code = preg_replace(array_keys($patterns), array_values($patterns), $code);

        // Manejo de Extends
        $code = preg_replace_callback('/@extends\(\'(.*?)\'\)/', function ($m) {
            $this->layout = $m[1];
            return '';
        }, $code);

        // Manejo de Secciones (Captura el contenido y lo guarda en el array)
        $code = preg_replace_callback('/@section\(\'(.*?)\'\)(.*?)@endsection/s', function ($m) {
            $this->sections[$m[1]] = $m[2];
            return '';
        }, $code);

        return $code;
    }

    public function clearCache()
    {
        $files = glob($this->cachePath . '*.php');
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
        }
        return count($files);
    }
}
