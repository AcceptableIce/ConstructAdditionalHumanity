<!DOCTYPE html>
<html>
<head>
<title>Construct Additional Humanity</title>
<link rel=stylesheet type="text/css" href="css/main.css">
</head>
<body>
<div class="header">
	<div class="main-title">Construct<br>Additional<br>Humanity</div>
	<div class="main-subtitle">A Cards Against Humanity&#0153;<br>expansion builder.</div>
	<div class="cardBox white" id="headerCard">
		<div class="cardTitle"><?
		$white_cards = array('Basement snakes.', 'Birds.', 'Alan x Helen.', 'Expecting Patrick to be awake past 9 PM.');
		echo $white_cards[array_rand($white_cards)];
		?></div>
		<div class="baseSetIcon"></div>
		<div class="cardSetName">Set Name</div>
	</div>
	<div class="cardBox" id="headerCard2">
		<div class="cardTitle"><?
		$black_cards = array("What is Alex wrong about this time?", "Of course Grant isn't home, he's too busy with ________.", "I'd let Shawn stay over, but I'm afraid of ________ again.",
							 "My hopes and dreams for ________ were crushed thanks to ________.");
		echo $black_cards[array_rand($black_cards)]; 
		?></div>
		<div class="baseSetIcon"></div>
		<div class="cardSetName">Set Name</div>
	</div>
</div>
<div class="main-data">
	<div class="form-row">
		<label class="form-label">Set Name</label>
		<input type="text" class="form-input" placeholder="<?
		$options = array('Cats Accept Handouts', 'Caterpillars Approach Hammocks', 'Crows Annex Holland', 'Cicadas Annoy Hipsters');
		echo $options[array_rand($options)];
		?>" data-bind="value: setName">
	</div>
	<div class="form-row">
		<label class="form-label">Set Image</label>
		<div class="img-prev" id="imgThumb">Drag Here</div>
		<div class="imgRules">
		64px by 64px PNG images only.<br>
		Maximum file size: 10kb
		</div>
	</div>
	<p>Drag a JSON file you exported before anywhere on this page to load it.</p>
</div>

<div class="addCard">
	<h2>Add a Card</h2>
	<div class="cardBox" data-bind="css: { white: cardType() == '0' }">
		<textarea class="cardTitle" id="cardEdit" data-bind="value: $root.currentlyEditingData, valueUpdate:'afterkeydown'" placeholder="Edit me."></textarea>
		<div class="baseSetIcon"></div>
		<div class="setIcon" data-bind="style: { 'background-image': $root.getIconUrl() }"></div>

		<div class="cardSetName" data-bind="text: $root.setName"></div>
		<div class="instructions draw2" data-bind="visible: cardType() == '2'">
			<div class="instRow"><div class="instName">PICK</div><div class="instAmt">2</div></div>
		</div>
		<div class="instructions draw3" data-bind="visible: cardType() == '3'">
			<div class="instRow"><div class="instName">DRAW</div><div class="instAmt">2</div></div>
			<div class="instRow"><div class="instName">PICK</div><div class="instAmt">3</div></div>
		</div>
	</div>
	<div class="addCardOptions">
		<div class="rowLabel">Card Type</div>
		<input type="radio" name="cardType" id="selWhite" value="0" data-bind="checked: cardType" /><label for="selWhite">White</label><br>
		<input type="radio" name="cardType" id="selBlack" value="1" data-bind="checked: cardType"  /><label for="selBlack">Black</label><br>
		<input type="radio" name="cardType" id="selBlack2" value="2" data-bind="checked: cardType" /><label for="selBlack2">Black, Pick 2</label><br>
		<input type="radio" name="cardType" id="selBlack3" value="3" data-bind="checked: cardType" /><label for="selBlack3">Black, Draw 2, Pick 3</label><br>
	
		<button id="submitCard" data-bind="click: submitCard">Add Card</button>
	</div>
	<div class="addCardTips">
		<h4>Tips for making cards that don't suck.</h4>
		<ul>
			<li>Just put one underscore for a blank. We'll make them all the same size anyways, regardless of how many you put.</li>
			<li>Press [Ctrl] + Enter to quickly add a card.</li>
			<li>The more open-ended your white cards are, the better. Ideally, most of your black cards should be able to work with every white card.</li>
			<li>Try out your black cards on some of your white cards to make sure they fit grammatically in most cases.</li>	
		</ul>
	
	</div>
</div>
<div class="setList">
	<h2>All Cards</h2>
	<div class="cardListing" data-bind="foreach: sortedCards">
		<div class="cardBox" data-bind="css: { white: type == '0' }">
			<div class="deleteCard" data-bind="click: $root.deleteCard">&times;</div>
			<div class="cardTitle" data-bind="text: value"></div>
			<div class="baseSetIcon"></div>
			<div class="setIcon" data-bind="style: { 'background-image': $root.getIconUrl() }"></div>
			<div class="cardSetName" data-bind="text: $root.setName"></div>
			<div class="instructions draw2" data-bind="visible: type == 2">
				<div class="instRow"><div class="instName">PICK</div><div class="instAmt">2</div></div>
			</div>
			<div class="instructions draw3" data-bind="visible: type == 3">
				<div class="instRow"><div class="instName">DRAW</div><div class="instAmt">2</div></div>
				<div class="instRow"><div class="instName">PICK</div><div class="instAmt">3</div></div>
			</div>
		</div>
	
	</div>
</div>
<div class="completeSet">
	<h2>All done?</h2>
	<p>Generating all those cards takes a lot of time. No matter how dire things become, only hit that button once.</p>
	<p class="complete-error" data-bind="text: $root.exportError"></p>
	<button id="exportSet" data-bind="click: exportSet">Generate!</button>
	<button id="saveSet" data-bind="click: saveSet">Export to JSON</button>
</div>
<div class="footer">
	Built by <a href="http://acceptableice.com"> Jake Roussel</a> (<a class="thick" href="http://twitter.com/acceptableice">@AcceptableIce</a>). 
	<span class="footer-small">Cards Against Humanity&#0153; is a trademark of Cards Against Humanity, LLC, and is in no way affiliated with this site or its contents.</span>
	
</div>
<script src="js/jquery-2.1.0.js"></script>
<script src="js/knockout.js"></script>
<script src="js/cardbuilder.js"></script>

</script>

</body>
</html>