<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<?= $this->Html->css('feedback.css') ?>

<div class="container roundGreyBox">
    <?=
        $this->Form->create(false, [
            'id' => 'feedbackForm'
            ]
        )
    ?>

    <br />
    <center><h1>Feedback</h1></center>
    <center><p>We appreciate you taking the time to visit our site.  If you have any suggestions on how to improve it, please leave a comment below!</p></center>

    <div class="textarea">
        <?=
            $this->Form->textarea('feedback', [
                'id' => 'feedbackID',
                'templates' => [
                    'inputContainer' => '{{content}}'
		],
                'rows' => '10',
                'cols' => '117'
            ])
        ?>
    </div>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn mb-3  btn-basic btn-lg sub-btn', 'id' => 'submit-btn', 'style' => 'float: right'], ['action' => 'userfeedback']); ?>
    <?= $this->Form->end() ?>
</div>

