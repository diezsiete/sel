<?php


namespace Sel\RemoteBundle\Helper\Api;


use Doctrine\ORM\Query;

class EndPointParams implements \ArrayAccess
{
    /**
     * @param EndPointParams|array $params
     * @param string $start
     * @return string
     */
    public static function build($params, $start = '?')
    {
        $query = '';
        foreach(['hydration', 'length', 'pagination', 'start'] as $param) {
            if (isset($params[$param])) {
                $query .= ($query ? '&' : '') . "$param=".$params[$param];
            }
        }

        foreach ($params['orders'] ?? [] as $sort => $order) {
            $query .= ($query ? '&' : '') . "order[$sort]=$order";
        }
        if (isset($params['search'])) {
            $query .= ($query ? '&' : '') . "search=".urlencode($params['search']);
        }

        return $start . $query;
    }

    /**
     * @param EndPointParams|array|null $params
     * @return array
     */
    public static function buildContext($params): array
    {
        $context = [];
        if ($params && $params['groups']) {
            $context['groups'] = is_array($params['groups']) ? $params['groups'] : [$params['groups']];
        }
        return $context;
    }

    public $hydration;
    public $length;
    public $pagination;
    public $start;
    public $search;
    public $orders = [];
    public $groups = [];

    public $denormalizeType;

    public function isHydrationArray(): bool
    {
        return $this->hydration === Query::HYDRATE_ARRAY || $this->hydration === 'array';
    }

    public function addOrderBy($sort, $order = 'asc')
    {
        $this->orders[$sort] = $order;
        return $this;
    }

    public function offsetExists($offset)
    {
        return property_exists($this, $offset) && $this->$offset !== null;
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        $this->$offset = null;
    }
}