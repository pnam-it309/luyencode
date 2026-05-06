<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\View\JsonView;

class ApiController extends AppController
{
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    public function initialize(): void
    {
        parent::initialize();
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        
        // Disable FormProtection if it exists
        if ($this->components()->has('FormProtection')) {
            $this->components()->unload('FormProtection');
        }
    }

    /**
     * Automatically handle JSON rendering for all API controllers
     */
    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
        
        $serialize = $this->viewBuilder()->getVar('_serialize');
        if ($serialize) {
            $data = [];
            foreach ((array)$serialize as $var) {
                $data[$var] = $this->viewBuilder()->getVar($var);
            }
            
            $response = $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($data));
            
            $this->setResponse($response);
            $event->stopPropagation();
            return $response;
        }
    }

    /**
     * Helper to return raw JSON response (manual fallback)
     */
    protected function jsonResponse($data, $status = 200)
    {
        return $this->response
            ->withStatus($status)
            ->withType('application/json')
            ->withStringBody(json_encode($data));
    }
}
