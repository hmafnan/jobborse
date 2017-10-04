<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Job $job
 */
use Cake\Routing\Router;
?>
<h2>Are you sure you want to delete this ?</h2>

<div class="jobs view large-9 medium-8 columns content">
    <h3><?= h($job->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($job->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($job->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $job->has('user') ? $this->Html->link($job->user->name, ['controller' => 'Users', 'action' => 'view', $job->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($job->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($job->description)); ?>
    </div>

    <?= $this->Form->postLink(
            __('Delete Job'),
            ['action' => 'delete', $job->id],
            ['style' => 'padding:10px;background-color: #da0707;color:#ffffff'],
            ['confirm' => __('Are you sure you want to delete # {0}?', $job->id)]) ?>
</div>
