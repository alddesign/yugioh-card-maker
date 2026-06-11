<?php
define('TITLE', 'Yu-Gi-Oh! Card Maker | Create Custom Yu-Gi-Oh! Cards for free');
define('DESCRIPTION', 'Create high-quality custom Yu-Gi-Oh! cards for free. Choose card types, attributes, and levels. Easy to use, mobile-friendly, and instant downloads.');
define('KEYWORDS', 'yugioh, yu-gi-oh, make yugioh cards, yugioh card maker, design your own yugioh card, design yugioh card, yugioh card creator, yugioh card generator, yugioh card download, yu-gi-oh cards, trading cards, trading card maker, yugioh deck builder, collectible card game, yugioh card art, card game, AI card generator, unique yugioh cards, yugioh art generator, yugioh card art, yugioh card art maker');

view('html-top');
?>

<header class="row mt-2">
	<div class="col-12">
		<h1 class="d-inline">Yu-Gi-Oh! Card Maker</h1>
		<span class="ml-2" aria-hidden="true"><?= VERSION ?></span>
	</div>
</header>

<hr class="mt-4 mb-4">

<main>
	<div class="row mb-4">
		<section class="col-lg-5 mb-4 p-0">
			<h2 class="sr-only">Card preview</h2>
			<div class="d-flex justify-content-center">
				<canvas id="canvas" width="694" height="1013" style="width: 694px; height: 1013px; background-color: #eee;"></canvas>
			</div>
		</section>
		
		<section class="col-lg-7 mb-4">
			<h2 class="sr-only">Card data form - enter your cards details</h2>
			<div id="form" role="form">
				<div class="row form-group">
					<div class="col-md-2 align-content-center">
						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" id="dark" checked>
							<label class="custom-control-label" for="dark">Dark mode</label>
						</div>
					</div>
					<div class="col-md-5">
						<div class="input-group">
							<input id="pictureUrl" class="form-control fld" type="text" placeholder="Picture URL" value=""/>
							<div class="input-group-append"><label for="pictureUrl" class="input-group-text">Picture Url</label></div>
						</div>
						<small id="pictureUrlError" class="form-text text-danger"></small>
					</div>
					<div class="col-md-5">
						<div class="custom-file">
							<label for="pictureFile" id="pictureFileLabel" class="custom-file-label">Upload Picture...</label>
							<input id="pictureFile" class="custom-file-input fld" data-show-preview="false" type="file" accept="image/*"/>
						</div>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md">
						<input id="pictureScale" class="form-control-range fld" type="range" min="10" max="200" step="5" value="100"/>
						<label for="pictureScale" class="m-0">
							<small>Image size <span id="pictureScaleValue">100</span>%.</small>
						</label>
						<small><i>Use drag &amp; drop to move the image on the card.</i></small>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="type" class="input-group-text">Type</label></div>
							<select id="type" type="select" class="form-control fld" placeholder="Card name">
								<option value="link">Link</option>
								<option value="effect">Effect</option>
								<option value="spell">Spell</option>
								<option value="xyz">Xyz</option>
								<option value="normal" selected>Normal</option>
								<option value="fusion">Fusion</option>
								<option value="ritual">Ritual</option>
								<option value="trap">Trap</option>
								<option value="synchro">Synchro</option>
								<option value="darksynchro">Dark Synchro</option>
								<option value="legendarydragon">Legendary Dragon</option>
								<option value="token">Token</option>
								<option value="obelisk">Obelisk</option>
								<option value="ra">Ra</option>
								<option value="slifer">Slifer</option>  
							</select>
						</div>
					</div>
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="attribute" class="input-group-text">Attribute</label></div>
							<select id="attribute" type="select" class="form-control fld" placeholder="Card name">
								<option value="dark">Dark</option>
								<option value="light" selected>Light</option>
								<option value="fire">Fire</option>
								<option value="earth">Earth</option>
								<option value="water">Water</option>
								<option value="wind">Wind</option>
								<option value="laugh">Laugh</option>
								<option value="divine">Divine</option>
								<option value="spell">Spell</option>
								<option value="trap">Trap</option>
								<option value="bossstart">Boss (start)</option>
								<option value="boss1">Boss (1)</option>
								<option value="boss2">Boss (2)</option>
								<option value="boss3">Boss (3)</option>
							</select>
						</div>
					</div>
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="level" class="input-group-text">Level</label></div>
							<select id="level" type="select" class="form-control fld" placeholder="Card name">
								<option value="0">0</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8" selected>8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
						</div>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md link-elem" style="display: none;">
						<fieldset class="row">
							<div class="col align-content-center">
								<legend>Link Arrows</legend>
							</div>
							<div class="col">
								<div id="link-grid">
									<input type="checkbox" id="link0" class="link fld" aria-label="Top left">
									<input type="checkbox" id="link1" class="link fld" aria-label="Top">
									<input type="checkbox" id="link2" class="link fld" aria-label="Top right">
									<input type="checkbox" id="link3" class="link fld" aria-label="Left">
									<span></span>
									<input type="checkbox" id="link4" class="link fld" aria-label="Right">
									<input type="checkbox" id="link5" class="link fld" aria-label="Bottom left">
									<input type="checkbox" id="link6" class="link fld" aria-label="Bottom">
									<input type="checkbox" id="link7" class="link fld" aria-label="Bottom right">
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="name" class="input-group-text">Name</label></div>
							<input id="name" type="text" class="form-control fld" placeholder="" value="Blue-Eyes White Dragon"/>
						</div>
					</div>
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="monsterType" class="input-group-text">Monster Type</label></div>
							<input id="monsterType" type="text" class="form-control fld" placeholder="" value="Dragon/Normal"/>
						</div>
					</div>
				</div>

				<div class="row form-group">	
					<div class="col-md">
						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input fld" id="pendulum">
							<label class="custom-control-label" for="pendulum">Pendulum</label>
						</div>
					</div>
					<div class="col-md pendulum-elem" style="display: none;">
						<div class="input-group">
							<div class="input-group-prepend"><label for="blue" class="input-group-text">Blue Scale</label></div>
							<input id="blue" type="number" class="form-control fld" placeholder="" value="1" min="0" max="13"/>
						</div>
					</div>
					<div class="col-md pendulum-elem" style="display: none;">
						<div class="input-group">
							<div class="input-group-prepend"><label for="red" class="input-group-text">Red Scale</label></div>
							<input id="red" type="number" class="form-control fld" placeholder="" value="1" min="0" max="13"/>
						</div>
					</div>
				</div>      

				<div class="row form-group pendulum-elem" style="display: none;">
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="pendulumEffects" class="input-group-text">Pendulum<br>Effects</label></div>
							<textarea id="pendulumEffects" class="form-control fld" placeholder="Pendulum Effects..." rows="4"></textarea>
						</div>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="effects" class="input-group-text">Effects</label></div>
							<textarea id="effects" class="form-control fld" placeholder="Effects..." rows="4">This legendary dragon is a powerful engine of destruction. Virtually invincible, very few have faced this awesome creature and lived to tell the tale.</textarea>
						</div>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="atk" class="input-group-text">ATK</label></div>
							<input id="atk" type="text" class="form-control fld" placeholder="" value="3000" maxlength="4"/>
						</div>
					</div>
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="def" class="input-group-text">DEF</label></div>
							<input id="def" type="text" class="form-control fld" placeholder="" value="2500" maxlength="4"/>
						</div>
					</div>

				</div>

				<div class="row form-group">
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="set" class="input-group-text">Set</label></div>
							<input id="set" type="text" class="form-control fld" placeholder="" value="1234-56789" maxlength="9"/>
						</div>
					</div>
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="serial" class="input-group-text">Serial</label></div>
							<input id="serial" type="text" class="form-control fld" placeholder="" value="89631139" maxlength="8"/>
						</div>
					</div>
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="edition" class="input-group-text">Edition</label></div>
							<input id="edition" type="text" class="form-control fld" placeholder="" value="1ˢᵗ Edition"/>
						</div>
					</div>
					<div class="col-md">
						<div class="input-group">
							<div class="input-group-prepend"><label for="copyright" class="input-group-text">&copy;</label></div>
							<input id="copyright" type="text" class="form-control fld" placeholder="" value="1996 KAZUMI TAKAHASHI"/>
						</div>
					</div>
				</div>

				<div class="row form-group mt-4">
					<div class="col-md">
						<button id="savepng" class="btn btn-primary fullwidth">Download Card (PNG)</button>
					</div>
					<div class="col-md">
						<button id="savewebp" class="btn btn-outline-primary fullwidth">Download Card (WEBP)</button>
					</div>
					<div class="col-md">
						<button id="savejpeg" class="btn btn-outline-primary fullwidth">Download Card (JPEG)</button>
					</div>
				</div>
			</div>
		</section>
	</div>

	<hr class="mt-4 mb-4">

	<div class="row mb-4">
		<div class="col-lg-2"></div>
		<section class="col-lg-8" aria-label="Description">
			<h2>Create Custom Yu-Gi-Oh! Cards Instantly</h2>
			<p>Welcome to the ultimate Yu-Gi-Oh! card maker. Whether you want to design a completely broken boss monster for casual duels with friends, bring an inside joke to life, or create custom proxy cards for testing new deck strategies, our free online generator makes it incredibly easy.</p>
			<p>With our real-time rendering engine, what you type into the creator form is exactly what you see on your screen. No complicated image editing software required—just fill in the details and watch your custom card come together instantly.</p>
			
			<h2>How to Use the Yu-Gi-Oh! Card Maker</h2>
			<ol>
				<li class="mb-4"><strong>Add Artwork Picture:</strong> Upload your custom image or use an URL to fill the artwork frame. Use the picture size slider to scale the artwork, and adjust the position with drag and drop.</li>
				<li class="mb-4"><strong>Input your Card Details:</strong> Select type, attribute, level, and type in all the relevant information for your card.</li>
				<li class="mb-4"><strong>Preview and Save:</strong> Check the live preview to make sure the text scaling and layout look exactly like you want it, then download your finished image.</li>
			</ol>

			<h2>Features of Our Yu-Gi-Oh! Card Maker</h2>
			<ul>
				<li class="mb-4">Real-Time Live Preview: See font picture adjustments, attribute changes, and text layouts adapt dynamically as you type.</li>
				<li class="mb-4">Full Customization Controls: Fully adjust names, card text, levels, stars, ATK/DEF values, and card types seamlessly.</li>
				<li class="mb-4">High-Quality Image Layouts: Designed to closely mimic authentic card borders, fonts, and bounding boxes for high-fidelity custom prints.</li>
				<li class="mb-4">100% Free to Use: Create as many custom monsters, spells, and traps as your deck requires without hidden paywalls.</li>
			</ul>
		</section>
		<div class="col-lg-2"></div>
	</div>
</main>

<footer>
	<hr class="mt-4 mb-4">
	<div class="row mb-4">
		<div class="col-12 text-center">
			<small>&copy; <?= date('Y') ?> Yu-Gi-Oh! Card Maker. All rights reserved.</small>
		</div>
	</div>
</footer>

<?php view('html-bottom'); ?>