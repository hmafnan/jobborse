<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;

/**
 * Jobs Controller
 *
 * @property \App\Model\Table\JobsTable $Jobs
 *
 * @method \App\Model\Entity\Job[] paginate($object = null, array $settings = [])
 */
class JobsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $jobs = $this->paginate($this->Jobs);

        $this->set(compact('jobs'));
        $this->set('_serialize', ['jobs']);
    }

    /**
     * View method
     *
     * @param string|null $id Job id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $job = $this->Jobs->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('job', $job);
        $this->set('_serialize', ['job']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $job = $this->Jobs->newEntity();
        if ($this->request->is('post')) {
            $job = $this->Jobs->patchEntity($job, $this->request->getData());
            $job['token'] = bin2hex(random_bytes(42));
            if ($this->Jobs->save($job)) {
                $this->sendEmail($job);
                $this->Flash->success(__('The job has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The job could not be saved. Please, try again.'));
        }
        $users = $this->Jobs->Users->find('list', ['limit' => 200]);
        $this->set(compact('job', 'users'));
        $this->set('_serialize', ['job']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Job id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $job = $this->Jobs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['get'])) {
            $token = $this->request->getQuery('token');
            if($token != $job['token']) {
                return $this->redirect(['controller' => 'Users', 'action'=>'login']);
            }
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $job = $this->Jobs->patchEntity($job, $this->request->getData());
            if ($this->Jobs->save($job)) {
                $this->Flash->success(__('The job has been saved'));
                return $this->redirect(['action' => 'message']);
            }
            $this->Flash->error(__('The job could not be saved. Please, try again.'));
        }
        $users = $this->Jobs->Users->find('list', ['limit' => 200]);
        $this->set(compact('job', 'users'));
        $this->set('_serialize', ['job']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Job id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $job = $this->Jobs->get($id);
        if ($this->Jobs->delete($job)) {
            $this->Flash->success(__('The job has been deleted.'));
            return $this->redirect(['action' => 'message']);
        } else {
            $this->Flash->error(__('The job could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete view method
     *
     * @param string|null $id Job id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteview($id = null)
    {
        $job = $this->Jobs->get($id);
        if ($this->request->is(['get'])) {
            $token = $this->request->getQuery('token');
            if($token != $job['token']) {
                return $this->redirect(['controller' => 'Users', 'action'=>'login']);
            }
        }
        $this->set(compact('job'));
        $this->set('_serialize', ['job']);

    }

    public function message()
    {

    }

    /**
     * Send email method
     *
     * Send with smtp
     * @param job.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function sendEmail($job) {
        $email = new Email();
        $email->setTransport('mailgun');
        $url = 'http://'.$this->request->host() . '/jobs/';
        $editUrl = $url . 'edit/' . $job->id . '?token=' . $job->token;
        $deleteUrl = $url . 'deleteview/' . $job->id . '?token=' . $job->token;
        $message = "To edit the job click on link below <br> <a href='$editUrl' target='_blank'>$editUrl</a>";
        $message = $message . "<br>To delete the job click on link below <br> <a href='$deleteUrl' target='_blank'>$deleteUrl</a>";
        $email->setFrom(['afnannazir.qc@gmail.com' => 'Job Added']);
        $email->setTo($job->email);
        $email->setSubject('New job added');
        $email->setEmailFormat('both');
        $email->send($message);
    }

    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['edit', 'message', 'deleteview', 'delete']);
    }
}
