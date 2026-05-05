<?php

namespace Core;

class MiniBlade
{
    protected array $sections = [];
    protected mixed $layout = null;
    protected mixed $viewsPath;

    public function __construct(string $viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, '/') . '/';
    }

    public function render(string $viewName, array $data = [])
    {
        $viewName = str_replace('.', '/', $viewName);

        // 1. Convertimos ['titulo' => 'Hola'] en $titulo
        // Usamos EXTR_SKIP para no sobreescribir variables internas de la función
        extract($data, EXTR_SKIP);

        $path = $this->viewsPath . $viewName . '.view.php';

        if (!file_exists($path)) {
            die("Error: La vista [$viewName] no existe en $path");
        }

        $content = file_get_contents($path);
        $compiled = $this->compile($content);

        ob_start();
        // Solo para probar:
        // return "<pre>" . htmlspecialchars($compiled) . "</pre>";
        // Usamos eval para ejecutar el PHP resultante de la compilación
        try {
            eval('?>' . $compiled);
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $output = ob_get_clean();

        if ($this->layout) {
            $parent = $this->layout;
            $this->layout = null;
            return $this->render($parent, $data);
        }

        return $output;
    }

    protected function compile(string $code)
    {
        // 1. Directivas de Control: @if, @else, @foreach
        // @if(condicion)
        $code = preg_replace('/@if\((.*)\)/', '<?php if($1): ?>', $code);

        // @else
        $code = preg_replace('/@else/', '<?php else: ?>', $code);

        // @endif
        $code = preg_replace('/@endif/', '<?php endif; ?>', $code);

        // @foreach($items as $item)
        // Soporta tanto ($items as $item) como ($items as $key => $value)
        $code = preg_replace('/@foreach\s*\((.*)\)/', '<?php foreach($1): ?>', $code);

        // @endforeach
        $code = preg_replace('/@endforeach/', '<?php endforeach; ?>', $code);

        // 2. Directiva @include('nombre_vista')
        // Esto llamará al método includeView de la clase en tiempo de ejecución
        $code = preg_replace('/@include\(\'(.*)\'\)/', '<?php echo $this->includeView("$1", get_defined_vars()); ?>', $code);

        // 3. Compilamos las variables {{ }}
        $code = preg_replace('/\{\{\s*(.*?)\s*\}\}/', '<?php echo htmlspecialchars((string)$1, ENT_QUOTES, "UTF-8"); ?>', $code);

        // 4. Luego las secciones (para que guarden el código PHP de las variables ya traducido)
        $code = preg_replace_callback('/@section\(\'(.*)\'\)(.*?)@endsection/s', function ($m) {
            return "<?php \$this->sections['$m[1]'] = <<<'EOT'\n$m[2]\nEOT;\n ?>";
        }, $code);

        // 5. Después el resto
        $code = preg_replace('/@yield\(\'(.*)\'\)/', '<?php eval("?>".($this->sections["$1"] ?? "")); ?>', $code);

        // Directiva @extends
        $code = preg_replace_callback('/@extends\(\'(.*)\'\)/', function ($m) {
            $this->layout = $m[1];
            return '';
        }, $code);

        return $code;
    }

    protected function includeView(string $viewName, array $data): string
    {
        // Reutilizamos la lógica de renderizado para el parcial
        // pero sin permitir que el parcial use @extends (por simplicidad)
        $viewName = str_replace('.', '/', $viewName);
        $path = $this->viewsPath . $viewName . '.view.php';

        if (!file_exists($path)) {
            return "<!-- Error: Parcial [$viewName] no encontrado -->";
        }

        $content = file_get_contents($path);
        $compiled = $this->compile($content);

        // 1. Convertimos ['titulo' => 'Hola'] en $titulo
        // Usamos EXTR_SKIP para no sobreescribir variables internas de la función
        extract($data, EXTR_SKIP);

        // Solo para probar:
        // return "<pre>" . htmlspecialchars($compiled) . "</pre>";
        // return "<pre>" . print_r($data) . "</pre>";

        ob_start();
        try {
            eval('?>' . $compiled);
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $output = ob_get_clean();

        return $output;
    }
}
