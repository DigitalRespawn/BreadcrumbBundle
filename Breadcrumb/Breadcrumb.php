<?php

namespace DigitalRespawn\BreadcrumbBundle\Breadcrumb;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class Breadcrumb
{
	protected $config;
	protected $router;
	protected $translator;
	protected $requestStack;
	protected $requestParamConverter;

	public function __construct(array $config, RouterInterface $router, TranslatorInterface $translator, RequestStack $requestStack, RequestParamConverter $requestParamConverter)
	{
		$this->config = $config;
		$this->router = $router;
		$this->translator = $translator;
		$this->requestStack = $requestStack;
		$this->requestParamConverter = $requestParamConverter;
	}

	/**
	 * Generate the breadcrumb as an array
	 *
	 * @return array $breadcrumb : Array of item composing the breadcrumb
	 *
	 * @throws \Exception : Malformed routes
	 */
	public function getBreadcrumb()
	{
		$breadcrumb = array();
		$routes = $this->router->getRouteCollection();
		$masterRequest = $this->requestStack->getMasterRequest();

		$uri = $this->router->generate($masterRequest->attributes->get('_route'), $masterRequest->attributes->get('_route_params'));
		$route = $routes->get($masterRequest->get('_route'));
		$params = $masterRequest->attributes->get('_route_params');
		$request = $this->requestParamConverter->getConvertedRequestFromRoute($route, $params);

		while (null !== $route) {
			try {
				$breadcrumbOptions = $route->getOption('breadcrumb');
				if (null === $breadcrumbOptions) {
					break;
				}
				if (!isset($breadcrumbOptions['label'])) {		// Don't display if no label
					continue;
				}

				$transDomain = isset($breadcrumbOptions['trans_domain']) ? $breadcrumbOptions['trans_domain'] : $this->config['trans_domain'];
				$transDelimiter = isset($breadcrumbOptions['trans_delimiter']) ? $breadcrumbOptions['trans_delimiter'] : $this->config['trans_delimiter'];

				if (isset($breadcrumbOptions['label_params'])) {	// Binding translation params if set
					$transParams = $this->bindValues($request, $breadcrumbOptions['label_params'], $transDelimiter);
				} else {
					$transParams = array();
				}

				// Translation of the label
				$label = $this->translator->trans($breadcrumbOptions['label'], $transParams, $transDomain, $request->getLocale());

				// Adding the item to the breadcrumb
				$breadcrumb[] = array(
					'uri' => $uri,
					'label' => $label
				);

				$route = null;
				if (!isset($breadcrumbOptions['parent'])) {	// Stops if no parent
					break;
				}

				if (isset($breadcrumbOptions['parent_params'])) {	// Binding parent's params if set
					$params = $this->bindValues($request, $breadcrumbOptions['parent_params']);
				} else {
					$params = array();
				}

				$route = $routes->get($breadcrumbOptions['parent']);	// Getting parent route
				$request = $this->requestParamConverter->getConvertedRequestFromRoute($route, $params);	// Bind params in request
				$uri = $this->router->generate($breadcrumbOptions['parent'], $params);	// Generating parent's URI
			}
			catch (\Exception $e) {
				if (!$this->config['enable_errors']) {	// If errors are disable -> stop breadcrumb
					$route = null;
				} else {
					throw new \Exception('Breadcrumb Error : Check your routes\' syntax');
				}
			}
		}

		return array_reverse($breadcrumb);
	}

	/**
	 * Bind values from request to an array of params
	 *
	 * @param Request $request : The request with converted params
	 * @param array $binding : Associative array of elements to bind
	 *
	 * @return array $params : Bound values
	 */
	public function bindValues(Request $request, array $binding, $delimiter = '')
	{
		$params = array();

		if (count($binding) > 0) {
			foreach ($binding as $key => $value) {
				$binding = explode('.', $value);
				$object = $request->get($binding[0]);
				for ($i = 1; $i < count($binding); ++$i) {
					if (is_array($object)) {
						$object = $object[$i];
					} else {
						$getter = 'get' . ucfirst($binding[$i]);
						$object = $object->$getter();
					}
				}
				$params[$delimiter . $key . $delimiter] = $object;
			}
		}

		return $params;
	}
}