<?php
/**
 * Request Panel Element
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         DebugKit 0.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * @type \DebugKit\View\AjaxView $this
 * @type array $headers
 * @type array $params
 * @type array $data
 * @type array $query
 * @type array $cookie
 * @type string $matchedRoute
 */
?>
<?php if (!empty($headers) && $headers['response']): ?>
<h4>Warning</h4>
    <?= '<p class="warning">' . __d('DebugKit', 'Headers already sent at file {0} and line {1}.', [$headers['file'], $headers['line']]) . '</p>' ?>
<?php endif; ?>

<h4>Routing Params</h4>
<?= $this->Toolbar->makeNeatArray($params) ?>

<h4>Post data</h4>
<?php
if (empty($data)):
    echo '<p class="info">' . __d('DebugKit', 'No post data.') . '</p>';
else:
    echo $this->Toolbar->makeNeatArray($data);
endif;
?>

<h4>Query string</h4>
<?php
if (empty($query)):
    echo '<p class="info">' . __d('DebugKit', 'No querystring data.') . '</p>';
else:
    echo $this->Toolbar->makeNeatArray($query);
endif;
?>

<h4>Cookie</h4>
<?php if (isset($cookie)): ?>
    <?= $this->Toolbar->makeNeatArray($cookie) ?>
<?php else: ?>
    <p class="info"><?= __d('DebugKit', 'No Cookie data.') ?></p>
<?php endif; ?>

<?php if (!empty($matchedRoute)): ?>
<h4>Matched Route</h4>
    <p><?= $this->Toolbar->makeNeatArray(['template' => $matchedRoute]) ?></p>
<?php endif; ?>
