<?php

namespace Muchacuba\ModelBundle\Util;

use Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;

class JsonResponse extends SymfonyJsonResponse
{
    /**
     * Constructor.
     *
     * @param mixed $data    The response data
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     */
    public function __construct($data = null, $status = 200, $headers = array())
    {
        parent::__construct(null, $status, $headers);

        if (null === $data) {
            $data = new \ArrayObject();
        }

        if ($data instanceof \Iterator) {
            $json = '[';
            $i = 0;
            foreach ($data as $item) {
                if ($i++ != 0) {
                    $json .= ', ';
                }

                $json .= json_encode($item);
            }
            $json .= ']';

            $this->data = $json;
            $this->update();
        } else {
            $this->setData($data);
        }
    }
}
