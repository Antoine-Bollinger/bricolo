<?php 
namespace Partez\Controllers;

use \Partez\Abstract;

final class {{ name }}Controller extends Abstract\Controller 
{
    /**
     * @Route("{{ route }}", name="{{ name }}")
     */
    public function init(

    ) {
        $this->renderPage("{{ name }}View.twig");
    }
}
