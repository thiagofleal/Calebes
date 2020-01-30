<div class="container">
	<?php if ($flash): ?>
		<div class="alert <?= $alert['type'] ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?= $alert['text'] ?>
		</div>
	<?php endif; ?>
	<h1><?= $title ?></h1>
	<hr>
	<form method="post" action="<?= $action ?>">
<?php foreach ($questions as $question): ?>
		<div class="card mb-5">
			<div class="card-header">
				<h5><?= $question->getNumber() ?> - <?= $question->getTitle() ?></h5>
			</div>
			<div class="card-body">
				<?= $question->getText() ?>
				<hr>
<?php foreach ($question->getOptions() as $option): ?>
<?php if ($question->getType() == 1): ?>
				<label><?= $option->getText() ?></label>
				<input type="hidden"
				name="answer[value][<?= $question->getNumber() ?>][]"
				value="<?= $option->getNumber() ?>">
<?php if ($option->getInsert()): ?>
				<textarea
				name="answer[extra][<?= $question->getNumber() ?>][<?= $option->getNumber() ?>]"
				class="form-control"
				required="required"></textarea>
<?php endif; ?>
<?php endif; ?>
<?php if ($question->getType() == 2): ?>
				<input type="radio"
				name="answer[value][<?= $question->getNumber() ?>][]"
				id="question_<?= $question->getNumber() ?>_option_<?= $option->getNumber() ?>"
				class="enable-on-mark"
				value="<?= $option->getNumber() ?>"
				data-enable="#question_<?= $question->getNumber() ?>_option_<?= $option->getNumber() ?>_text" />
				<label
				for="question_<?= $question->getNumber() ?>_option_<?= $option->getNumber() ?>" />
					<?= $option->getText() ?>
				</label>
				<br>
<?php if ($option->getInsert()): ?>
				<input type="text"
				name="answer[extra][<?= $question->getNumber() ?>][<?= $option->getNumber() ?>]"
				class="form-control"
				id="question_<?= $question->getNumber() ?>_option_<?= $option->getNumber() ?>_text"
				disabled="disabled" />
<?php endif; ?>
<?php endif; ?>
<?php if ($question->getType() == 3): ?>
				<input type="checkbox"
				name="answer[value][<?= $question->getNumber() ?>][]"
				id="question_<?= $question->getNumber() ?>_option_<?= $option->getNumber() ?>"
				class="enable-on-mark"
				value="<?= $option->getNumber() ?>"
				data-enable="#question_<?= $question->getNumber() ?>_option_<?= $option->getNumber() ?>_text" />
				<label
				for="question_<?= $question->getNumber() ?>_option_<?= $option->getNumber() ?>">
					<?= $option->getText() ?>
				</label>
				<br>
<?php if ($option->getInsert()): ?>
				<input type="text"
				name="answer[extra][<?= $question->getNumber() ?>][<?= $option->getNumber() ?>]"
				class="form-control"
				id="question_<?= $question->getNumber() ?>_option_<?= $option->getNumber() ?>_text"
				disabled="disabled" />
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
			</div>
		</div>
<?php endforeach; ?>
	<hr>
	<button type="submit" class="btn btn-primary">
		Salvar
	</button>
	<button type="button" class="btn btn-danger btn-clear-form">
		Cancelar
	</button>
	</form>
</div>