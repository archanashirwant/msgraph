<?php

class Router
{

    static public function parse($url, $request)
    {
        $url = trim($url);

        if ($url == "/msgraph/")
        {
            $request->controller = "Auth";
            $request->action = "signin";
            $request->params = [];
        }
        else
        {
            $explode_url = explode('/', $url);
            $explode_url = array_slice($explode_url, 2);
            $request->controller = $explode_url[0];
            $request->action = strtok($explode_url[1],'?');
			$str = parse_url($url, PHP_URL_QUERY);
			parse_str($str, $param);
			$request->params = $param;
			
        }

    }
}
?>