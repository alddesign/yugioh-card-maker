//#region vars and config
/** @type {HTMLCanvasElement} */
var canvas = null;
/** @type {CanvasRenderingContext2D} */
var ctx = null;
var data = 
{
	/** @type {HTMLImageElement} */
	picture: null,
	pictureScale: 1,
	type: '',
	attribute: '',
	level: 0,
	name: '',
	monsterType: '',
	effects: '',
	pendulumEffects: '',
	red: 0,
	blue: 0,
	atk: '',
	def: '',
	set: '',
	serial: '',
	edition: '',
	copyright: '',
	pendulum: false,
	links: [],
	linkscount: 0
}

/** For saving WEBP images */
var imageQualityWebp = 0.9;
/** For saving JPEG images */
var imageQualityJpeg = 0.9;
/** For uploading WEBP to server*/
var imageQualityWebpUpload = 0.9;
var defaultPictureUrl = baseUrl + 'img/_default.webp';
//#endregion

//#region Main
$(document).ready(async function()
{
	canvas = $('#canvas')[0];
	ctx = canvas.getContext('2d');

	registerEventHandlers();
	restoreValues();

	await font('YugiohMonsterType', 'fonts/ITCStoneSerifSmallCapsBold.woff2');
	await font('YugiohName', 'fonts/MatrixRegularSmallCaps1.woff2');
	await font('YugiohEffect', 'fonts/MatrixBook.woff2');
	await font('YugiohAtkDef', 'fonts/MatrixBoldSmallCaps.woff2');
	await font('YugiohCopyrightSetSerial', 'fonts/StoneSerifMedium.woff2');
	await font('YugiohEdition', 'fonts/StoneSerifSemibold.woff2');
	await font('YugiohLink', 'fonts/audiowide.woff2');
	await loadPictureFromUrl(defaultPictureUrl);
});

function registerEventHandlers()
{
	$('#pictureUrl').on('change', async () => 
	{
		loadPictureFromUrl();
	});

	$('#pictureFile').on('change', async () => 
	{
		loadImageFromFile();
	});

	$('.fld').on('input', () => 
	{
		Renderer.render();
	});

	$('#type').on('input', () =>
	{
		$('#type').val() === 'link' ? $('.link-elem').show() : $('.link-elem').hide();
	});

	$('#pendulum').on('change', () =>
	{
		$('#pendulum').is(':checked') ? $('.pendulum-elem').show() : $('.pendulum-elem').hide()
	});

	$('#pictureScale').on('input', () =>
	{
		$('#pictureScaleValue').text($('#pictureScale').val());
	});

	$('#dark').on('change', () =>
	{
		let checked = $('#dark').is(':checked');
		$('body').toggleClass('dark', checked);
		lsSet('dark', checked);
	});

	$(canvas).on('mousedown', (e) => { Drag.startDrag(e); });
	$(canvas).on('touchstart', (e) => { Drag.startDrag(e); });

	$(canvas).on('mousemove', (e) => { Drag.onDrag(e); });
	$(canvas).on('touchmove', (e) => { Drag.onDrag(e); });

	$(canvas).on('mouseup', (e) => { Drag.stopDrag(e); });
	$(canvas).on('touchend', (e) => { Drag.stopDrag(e); });

	$('#savepng').on('click', () => {save('png');});
	$('#savewebp').on('click', () => {save('webp', imageQualityWebp);});
	$('#savejpeg').on('click', () => {save('jpeg', imageQualityJpeg);});
}
//#endregion

//#region Renderer
class Renderer
{
	static async render()
	{
		await this.#loadData();
		await this.#renderBase();
		if(data.pendulum)
			await this.#renderPendulum();
		else
			await this.#renderNormal();
	}

	static async #renderBase()
	{
		ctx.drawImage(await img('img/base_border.webp'), 0, 0);
		ctx.drawImage(await img(`img/type_${data.type}.webp`), 23, 23);
		ctx.drawImage(await img('img/base_header.webp'), 36, 38);
		ctx.drawImage(await img(`img/attribute_${data.attribute}.webp`), 581, 44);
	}

	static async #renderNormal()
	{
		ctx.drawImage(await img(`img/base_artbox.webp`), 59, 161);
		ctx.drawImage(await img('img/base_lorebackground.webp'), 42, 754);
		ctx.drawImage(await img('img/base_loreborder.webp'), 30, 743);
		ctx.drawImage(await img('img/base_line.webp'), 52, 921);
		ctx.drawImage(await img('img/base_holo.webp'), 637, 955);

		this.#renderName();
		await this.#renderLevel();

		this.#fontstyle('YugiohMonsterType', 26, 'left', 'top', '#000');
		ctx.fillText(`[${data.monsterType}]`, 51, 762, 586);

		this.#fontstyle('YugiohEffect', 20, 'left', 'top', '#000');
		drawBreakingText(data.effects, 53, 791, 586);

		this.#renderAtkDef();
		this.#renderAdditionalInfo();

		this.renderPicture();

		await this.#renderLinks();
	}

	static async #renderPendulum()
	{

		ctx.drawImage(await img('img/pendulum_background.webp'), 23, 23);
		ctx.drawImage(await img(`img/pendulum_artbox.webp`), 31, 168);
		this.renderPicture();

		ctx.drawImage(await img('img/pendulum_lorebox.webp'), 30, 623);
		ctx.drawImage(await img('img/base_line.webp'), 52, 921);
		ctx.drawImage(await img('img/base_holo.webp'), 637, 955);

		this.#renderName();
		await this.#renderLevel();

		this.#fontstyle('YugiohEffect', 20, 'left', 'top', '#000');
		drawBreakingText(data.pendulumEffects, 110, 646, 475);
		
		this.#fontstyle('YugiohMonsterType', 26, 'left', 'top', '#000');
		ctx.fillText(`[${data.monsterType}]`, 51, 788, 586);

		this.#fontstyle('YugiohEffect', 20, 'left', 'top', '#000');
		drawBreakingText(data.effects, 53, 818, 586);

		this.#fontstyle('YugiohName', 60, 'center', 'top', '#000');
		ctx.fillText(data.blue, 69, 691, 50);
		ctx.fillText(data.red, 623, 691, 50);

		this.#renderAtkDef();
		this.#renderAdditionalInfo();

		await this.#renderLinks();
	}

	static renderPicture()
	{
		let scale = 100 / data.pictureScale;
		if(data.pendulum)
		{
			ctx.clearRect(45, 182, 603, 449);
			if(data.picture)
			{
				//img, clip start x, clip start y, clip w, clip h, img pos x, img pos, img w, img h
				ctx.drawImage(data.picture, -Drag.offsetX, -Drag.offsetY, 603 * scale, 449 * scale, 45, 182, 603, 449);
			}
		}
		else
		{
			ctx.clearRect(84, 186, 526, 526);
			if(data.picture)
			{
				//img, clip start x, clip start y, clip w, clip h, img pos x, img pos, img w, img h
				ctx.drawImage(data.picture, -Drag.offsetX, -Drag.offsetY, 526 * scale, 526 * scale, 84, 186, 526, 526);
			}
		}

		Drag.isTicking = false;
	}

	static #renderAdditionalInfo()
	{
		let color = (data.type === 'xyz' || data.type === 'darksynchro') && !data.pendulum ? '#fff' : '#000';	

		this.#fontstyle('YugiohCopyrightSetSerial', 20, 'right', 'top', color);
		if(data.pendulum)
		{
			ctx.textAlign = 'left';
			ctx.fillText(data.set, data.type === 'link' ? 51 : 61, 930);
		}
		else
		{
			ctx.fillText(data.set, data.type === 'link' ? 559 : 619, 729);
		}

		ctx.textAlign = 'right';
		ctx.fillText(`© ${data.copyright}`, 627, 968);
		
		this.#fontstyle('YugiohCopyrightSetSerial', 20, 'left', 'top', color);
		ctx.fillText(data.serial, 32, 968);

		this.#fontstyle('YugiohEdition', 20, 'left', 'top', color);
		ctx.fillText(data.edition, 130, 968);
	}

	static #renderName()
	{
		let color;
		switch(data.type)
		{

			case 'link': 
			case 'spell':
			case 'trap': 
			case 'xyz':
			case 'darksynchro':
				color = '#fff'; break;
			default: 
				color = '#000'; 
				break;
		}

		this.#fontstyle('YugiohName', 74, 'left', 'top', color);
		ctx.fillText(data.name, 52, 34, 525);
	}

	static async #renderLevel()
	{
		let image;
		let offset;
		let start;
		switch(data.type)
		{
			case 'link': 
			case 'spell':
			case 'trap':
				return;
			case 'xyz':
				image = await img('img/star_xyz.webp');
				offset = 46;
				start = 72;
				break;
			case 'darksynchro':
				image = await img('img/star_negative.webp');
				offset = 46;
				start = 72;
				break;
			default: 
				image = await img('img/star.webp');
				offset = -46;
				start = 578;
				break;
		}

		for(let i = 0; i < data.level; i++)
		{
			ctx.drawImage(image, start + (i * offset), 122);
		}
	}

	static async #renderLinks()
	{
		if(data.type !== 'link') 
			return;
	
		if(data.pendulum)
		{
			ctx.drawImage(await img(`img/link_lefttop${data.links[0] ? 'on' : ''}.webp`), 16, 148);
			ctx.drawImage(await img(`img/link_top${data.links[1] ? 'on' : ''}.webp`), 271, 133);
			ctx.drawImage(await img(`img/link_righttop${data.links[2] ? 'on' : ''}.webp`), 596, 148);
			ctx.drawImage(await img(`img/link_left${data.links[3] ? 'on' : ''}.webp`), -4, 470);
			ctx.drawImage(await img(`img/link_right${data.links[4] ? 'on' : ''}.webp`), 645, 470);
			ctx.drawImage(await img(`img/link_leftbottom${data.links[5] ? 'on' : ''}.webp`), 16, 902);
			ctx.drawImage(await img(`img/link_bottom${data.links[6] ? 'on' : ''}.webp`), 271, 951);
			ctx.drawImage(await img(`img/link_rightbottom${data.links[7] ? 'on' : ''}.webp`), 596, 902);
		}
		else
		{
			ctx.drawImage(await img(`img/link_lefttop${data.links[0] ? 'on' : ''}.webp`), 46, 148);
			ctx.drawImage(await img(`img/link_top${data.links[1] ? 'on' : ''}.webp`), 270, 137);
			ctx.drawImage(await img(`img/link_righttop${data.links[2] ? 'on' : ''}.webp`), 566, 148);
			ctx.drawImage(await img(`img/link_left${data.links[3] ? 'on' : ''}.webp`), 35, 373);
			ctx.drawImage(await img(`img/link_right${data.links[4] ? 'on' : ''}.webp`), 607, 373);
			ctx.drawImage(await img(`img/link_leftbottom${data.links[5] ? 'on' : ''}.webp`), 46, 670);
			ctx.drawImage(await img(`img/link_bottom${data.links[6] ? 'on' : ''}.webp`), 270, 708);
			ctx.drawImage(await img(`img/link_rightbottom${data.links[7] ? 'on' : ''}.webp`), 566, 670);
		}
	}

	static #renderAtkDef()
	{
		this.#fontstyle('YugiohAtkDef', 37, 'left', 'top', '#000');
		ctx.fillText('ATK/', 379, 916);
		ctx.textAlign = 'right';
		ctx.fillText(data.atk, 498, 916);

		if(data.type !== 'link')
		{
			ctx.textAlign = 'left';
			ctx.fillText('DEF/', 520, 916);
			ctx.textAlign = 'right';
			ctx.fillText(data.def, 637, 916);
		}
		else
		{
			this.#fontstyle('YugiohLink', 26, 'left', 'top', '#000', '900');
			ctx.fillText(`LINK-`, 520, 926);
			ctx.textAlign = 'right';
			ctx.fillText(`${data.linkscount}`, 637, 926);
		}
	}

	static #fontstyle(font, size, align, base, fill, fontweight = 'normal') 
	{
		ctx.font = `${fontweight} ${size}px ${font}`;
		ctx.textAlign = align;
		ctx.textBaseline = base;
		ctx.fillStyle = fill;
	}

	static async #loadData()
	{
		data.pictureScale = parseInt($('#pictureScale').val());
		data.type = $('#type').val();
		data.attribute = $('#attribute').val();
		data.level = parseInt($('#level').val());
		data.name = $('#name').val();
		data.monsterType = $('#monsterType').val();
		data.effects = $('#effects').val();
		data.pendulum = $('#pendulum').is(':checked');
		data.pendulumEffects = $('#pendulumEffects').val();
		data.red = parseInt($('#red').val());
		data.blue = parseInt($('#blue').val());
		data.atk = $('#atk').val();
		data.def = $('#def').val();
		data.set = $('#set').val();
		data.serial = $('#serial').val();
		data.edition = $('#edition').val();
		data.copyright = $('#copyright').val();
		data.linkscount = $('.link:checked').length;
		data.links = [];
		for(let i = 0; i <= 7; i++)
		{
			data.links[i] = $(`#link${i}`).is(':checked');
		}
		
		lsSet('data', JSON.stringify(data));
	}
}
//#endregion

//#region Drag and Drop
class Drag
{
	static mouseXStart = 0;
	static mouseYStart = 0;
	static offsetX = 0;
	static offsetY = 0;
	static isDragging = false;
	static isTicking = false;

	static cursorX(e)
	{
		return parseInt(e.touches ? e.touches[0].clientX : e.clientX);
	}

	static cursorY(e)
	{
		return parseInt(e.touches ? e.touches[0].clientY : e.clientY);
	}

	static startDrag(e)
	{
		if(!this.isDragging)
		{
			this.isDragging = true;
			this.mouseXStart = this.cursorX(e) - this.offsetX;
			this.mouseYStart = this.cursorY(e) - this.offsetY;

			//Disable image smoothing while dragging for better performance, it will be reenabled once the dragging stops
			ctx.imageSmoothingEnabled = false;
		}
	}

	static stopDrag(e)
	{
		//Reenable image smoothing once dragging stops
		ctx.imageSmoothingEnabled = true;

		//Perform a full render, to ensure correct z-index of all elements (picture not overlapping links arrows for example)
		Renderer.render();
		this.isDragging = false;
	}

	static onDrag(e)
	{
		if(this.isDragging)
		{
			e.preventDefault();

			this.offsetX = this.cursorX(e) - this.mouseXStart;
			this.offsetY = this.cursorY(e) - this.mouseYStart;

			if(this.isTicking === false)
			{
				this.isTicking = true;

				//Render only the picture while dragging for better performance, the rest of the card will be rendered once the dragging stops
				requestAnimationFrame(Renderer.renderPicture);
			}
		}
	}
}
//#endregion

//#region Helper functions
function restoreValues()
{
	$('#dark').prop('checked', lsGet('dark', 'true') === 'true');
	$('#dark').trigger('change');
}

/** localStorage.getItem() with default value */
function lsGet(key, def = '')
{
	let value = localStorage.getItem(key);
	return value === null ? def : value;
}

/** localStorage.setItem() */
function lsSet(key, value)
{
	localStorage.setItem(key, value);
}

/** Loads a custom font and adds it to the DOM */
async function font(name, url)
{
	const font = new FontFace(name, `url(${url})`, {display: 'swap'});
	await font.load();
	document.fonts.add(font);
}

/** Downloads the canvas as an image */
function save(ext, quality)
{
	let dataUrl = canvas.toDataURL(`image/${ext}`, quality);
	let link = document.createElement('a');
	link.download = `yugioh_${Date.now()}.${ext}`;
	link.href = dataUrl;
	link.target = '_self';
	link.click();

	if(!isLocal)
	{
		//Convert the canvas data to WEBP blod and send it to the server
		canvas.toBlob((data) => 
		{
			let formData = new FormData();
			formData.append('token', token);
			formData.append('data', data);

			fetch('/save/', 
			{
				method: 'POST',
				body: formData
			});

		}, 'image/webp', imageQualityWebpUpload);
	}
}

/**
 * Loads an image from an URL and returns a HTMLImageElement Promise
 * @param {string} url - The source of the image
 * @returns {Promise<HTMLImageElement>}
 */
function img(url) 
{
	return new Promise((resolve, reject) => 
	{
		const img = new Image();
		
		img.onload = () => resolve(img); // Success!
		img.onerror = (err) => reject(err); // Something went wrong (404, etc.)
		
		img.src = url;
	});
}

/** Loads the artwork picture from an URL */
async function loadPictureFromUrl(url = '')
{
	url = url || $('#pictureUrl').val();
	if(!url || !URL.parse(url)) 
		return;

	data.picture = null;

	//Fetch the image form the server to avoid CORS issues.
	//Response is raw WEBP data which is then converted to a base64 string and loaded as an image.
	fetch('/imagedata/', 
	{
		method: 'POST',
		body: new URLSearchParams({token: token, url: url})
	})
	.then((response) => 
	{
		return response.arrayBuffer();
	})
	.then(async (buffer) =>
	{
		data.picture = await img('data:image/webp;base64,' + arrayBufferToBase64(buffer));
		Renderer.render();
	})
	.catch(err => console.error('Error fetching image data:', err));
}

/** Loads the artwork picture from a local file */
function loadImageFromFile()
{
	let file = $('#pictureFile')[0].files[0];
	
	data.picture = null;
	if(file != undefined)
	{
		let reader = new FileReader();
		reader.onload = async (ev) => 
		{
			$('#pictureUrl').val('');
			$('#pictureFileLabel').text(file.name);
			data.picture = await img(reader.result);

			Renderer.render();
		};
		reader.readAsDataURL(file);
	}
}

function arrayBufferToBase64(buffer) 
{
    let binary = '';
    const bytes = new Uint8Array(buffer);
    const len = bytes.byteLength;
    
    for (let i = 0; i < len; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    
    return btoa(binary);
}

/**
* Draws a text with linke breaks on `ctx`
* @arg {string} str - text to be drawn
* @arg {number} x - top left x coordinate of the text
* @arg {number} y - top left y coordinate of the text
* @arg {number} w - maximum width of drawn text
* @arg {number} lh - line height
*/
function drawBreakingText(str, x, y, w, lh, method) 
{ 
	// local variables and defaults
	var textSize = parseInt(ctx.font.replace(/\D/gi, ''));
	var textParts = [];
	var textPartsNo = 0;
	var words = [];
	var currLine = '';
	var testLine = '';
	str = str || '';
	x = x || 0;
	y = y || 0;
	w = w || ctx.canvas.width;
	lh = lh || 1;
	method = method || 'fill';

	// manual linebreaks
	textParts = str.split('\n');
	textPartsNo = textParts.length;

	// split the words of the parts
	for (var i = 0; i < textParts.length; i++) {
		words[i] = textParts[i].split(' ');
	}

	// now that we have extracted the words
	// we reset the textParts
	textParts = [];

	// calculate recommended line breaks
	// split between the words
	for (var i = 0; i < textPartsNo; i++) {

		// clear the testline for the next manually broken line
		currLine = '';

		for (var j = 0; j < words[i].length; j++) {
			testLine = currLine + words[i][j] + ' ';

			// check if the testLine is of good width
			if (ctx.measureText(testLine).width > w && j > 0) {
				textParts.push(currLine);
				currLine = words[i][j] + ' ';
			} else {
				currLine = testLine;
			}
		}
		// replace is to remove trailing whitespace
		textParts.push(currLine);
	}

	// render the text on the canvas
	for (var i = 0; i < textParts.length; i++) {
		if (method === 'fill') {
			ctx.fillText(textParts[i].replace(/((\s*\S+)*)\s*/, '$1'), x, y+(textSize*lh*i));
		} else if (method === 'stroke') {
			ctx.strokeText(textParts[i].replace(/((\s*\S+)*)\s*/, '$1'), x, y+(textSize*lh*i));
		} else if (method === 'none') {
			return {'textParts': textParts, 'textHeight': textSize*lh*textParts.length};
		} else {
			console.warn('drawBreakingText: ' + method + 'Text() does not exist');
			return false;
		}
	}

	return {'textParts': textParts, 'textHeight': textSize*lh*textParts.length};
}
//#endregion