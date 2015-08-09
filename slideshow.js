/**
 * JavaScript of Slideshow_XH.
 *
 * @copyright   Copyright (c) 2012-2015 Christoph M. Becker <http://3-magi.net/>
 * @license     http://www.gnu.org/licenses/gpl.html GNU GPLv3
 * @version     $Id$
 * @link        <http://3-magi.net/?CMSimple_XH/Slideshow_XH>
 * @see         <https://hacks.mozilla.org/2011/08/animating-with-javascript-from-setinterval-to-requestanimationframe/>
 */


/**
 * The plugin's namespace.
 */
var slideshow = {}


/**
 * The interval in milliseconds between two animation frames.
 */
slideshow.FRAME_DURATION = 1000 / 50;


/**
 * The time in milliseconds,
 * which triggers the rendering of a new animation frame.
 */
slideshow.MAX_DELTA_T = 10 * slideshow.FRAME_DURATION;


/**
 * The request animation frame function.
 */
slideshow.RAF = window.requestAnimationFrame
    || window.mozRequestAnimationFrame
    || window.webkitRequestAnimationFrame
    || window.msRequestAnimationFrame
    || window.oRequestAnimationFrame;


/**
 * Main slideshow class.
 *
 * @constructor
 *
 * @param {String} id
 * @param {String} effect
 * @param {String} easing
 * @param {Number} delay
 * @param {Number} pause
 * @param {Number} duration
 */
slideshow.Show = function(id, effect, easing, delay, pause, duration) {
    var that;

    that = this;

    this.elt = window.document.getElementById(id);
    switch (effect) {
    case 'fade':
        this.effect = new slideshow.Fader(this);
        break;
    case 'slide':
        this.effect = new slideshow.Slider(this);
        break;
    case 'curtain':
        this.effect = new slideshow.Curtain(this);
        break;
    default:
        this.effect = new slideshow.Random(this);
        break;
    }
    this.easing = slideshow.easing[easing]
        ? slideshow.easing[easing]
        : slideshow.easing.easeInOut;
    this.pause = pause;
    this.duration = duration;

    this.current = this.elt.firstChild.nextSibling;
    this.init();
    this.running = 0;
    this.lastFrame = null;
    setTimeout(function() {that.animate()}, delay + pause);
}


/**
 * Returns the previous `image' element.
 *
 * @private
 *
 * @returns {HTMLImageElement}
 */
slideshow.Show.prototype.getPrevious = function() {
    return this.current.previousSibling
        ? this.current.previousSibling
        : this.current.parentNode.lastChild.previousSibling;
}


/**
 * Returns the next `image' element.
 *
 * @private
 *
 * @returns {HTMLImageElement}
 */
slideshow.Show.prototype.next = function() {
    this.current = this.current.nextSibling.nextSibling
        ? this.current.nextSibling
        : this.current.parentNode.firstChild;
    return this.current;
}


/**
 * Initializes the slideshow.
 *
 * @private
 *
 * @returns {undefined}
 */
slideshow.Show.prototype.init = function() {
    var clone, style;

    // Insert a clone of the first image with static position
    // to force height of the surrounding `div' to be greater than 0,
    // before absolutely positioning the first image.
    clone = this.elt.firstChild.cloneNode(false);
    style = clone.style;
    //style.position = "static";
    style.visibility = "hidden";
    this.elt.appendChild(clone);
    this.elt.firstChild.style.position = "absolute";

    style = this.current.style;
    style.display = "block";
    this.effect.prepare();
}


/**
 * Executes the next animation step.
 *
 * @private
 *
 * @returns {undefined}
 */
slideshow.Show.prototype.animate = function() {
    var that, now, deltaT;

    that = this;
    if (this.lastFrame === null) {
        this.lastFrame = new Date().getTime();
    }
    now = new Date().getTime();
    if (this.running < 1) {
        if (slideshow.RAF) {
            slideshow.RAF.call(window, function() {
                that.animate();
            }, this.elt);
        } else {
            setTimeout(function() {
                that.animate()
            }, slideshow.FRAME_DURATION);
        }
        deltaT = now - this.lastFrame;
        if (deltaT < slideshow.MAX_DELTA_T) {
            this.render(deltaT);
        }
        this.lastFrame = now;
    } else {
        this.lastFrame = null;
        this.running = 0;
        setTimeout(function() {
            that.animate()
        }, this.pause);
    }
}


/**
 * Renders the animation.
 *
 * @private
 *
 * @param   {Number} deltaT
 * @returns {undefined}
 */
slideshow.Show.prototype.render = function(deltaT) {
    var img, prev;

    img = this.current;
    img.style.zIndex = 2;

    this.running += deltaT / this.duration;
    this.running = Math.min(this.running, 1);

    this.effect.step(this.easing(this.running));

    if (this.running >= 1) {
        prev = this.getPrevious();
        prev.style.zIndex = 0;
        prev.style.display = "none";
        img.style.zIndex = 1;
        img = this.next();
        img.style.display = "block";
        this.effect.prepare();
    }
}


/**
 * The `fade' effect class.
 *
 * @constructor
 *
 * @param {slideshow.Show} show
 */
slideshow.Fader = function(show) {
    this.show = show;
}


/**
 * Sets the opacity.
 *
 * @private
 *
 * @param   {HTMLImageElement} img
 * @param   {Number} val
 * @returns {undefined}
 */
slideshow.Fader.setOpacity = function(img, val) {
    if (typeof img.style.opacity != 'undefined') {
        img.style.opacity = val;
    } else {
        img.style.filter = "alpha(opacity = " + Math.round(100 * val) + ")";
    }
}


/**
 * Prepares the next image.
 *
 * @public
 *
 * @returns {undefined}
 */
slideshow.Fader.prototype.prepare = function() {
    slideshow.Fader.setOpacity(this.show.current, 0);
}


/**
 * Executes the next animation step.
 *
 * @public
 *
 * @param   {Number} progress
 * @returns {undefined}
 */
slideshow.Fader.prototype.step = function(progress) {
    slideshow.Fader.setOpacity(this.show.current, progress);
}


/**
 * The `slide' effect class.
 *
 * @constructor
 *
 * @param {slideshow.Show} show
 */
slideshow.Slider = function(show) {
    this.show = show;
}


/**
 * Prepares the next image.
 *
 * @public
 *
 * @returns {undefined}
 */
slideshow.Slider.prototype.prepare = function() {
    var img;

    this.show.getPrevious().style.left = "0px";
    img = this.show.current;
    img.style.left = - img.offsetWidth + "px";
}


/**
 * Executes the next animation step.
 *
 * @public
 *
 * @param   {Number} progress
 * @returns {undefined}
 */
slideshow.Slider.prototype.step = function(progress) {
    var img;

    img = this.show.getPrevious();
    img.style.left = progress * img.offsetWidth + "px";
    img = this.show.current;
    img.style.left = (progress - 1) * img.offsetWidth + "px";
}


/**
 * The `curtain' effect class.
 *
 * @constructor
 *
 * @param {slideshow.Show} show
 */
slideshow.Curtain = function(show) {
    this.show = show;
}


/**
 * Prepares the next image.
 *
 * @public
 *
 * @returns {undefined}
 */
slideshow.Curtain.prototype.prepare = function() {
    var img;

    img = this.show.current;
    img.style.top = -img.offsetHeight + "px";
}


/**
 * Executes the next animation step.
 *
 * @public
 *
 * @param   {Number} progress
 * @returns {undefined}
 */
slideshow.Curtain.prototype.step = function(progress) {
    var img;

    img = this.show.current;
    img.style.top = (progress - 1) * img.offsetHeight + "px";
}


/**
 * The `random' effect.
 *
 * Switch randomly between all available effects.
 *
 * @constructor
 *
 * @param   {slideshow.Show} show
 * @returns {undefined}
 */
slideshow.Random = function(show) {
    this.show = show;
    this.effects = [new slideshow.Fader(show),
                    new slideshow.Slider(show),
                    new slideshow.Curtain(show)];
    this.effect = -1;
}

/**
 * Prepares the next image.
 *
 * @public
 *
 * @returns {undefined}
 */
slideshow.Random.prototype.prepare = function() {
    this.effect = Math.floor(3 * Math.random());
    this.effects[this.effect].prepare();
}

/**
 * Executes the next animation step.
 *
 * @public
 *
 * @param   {Number} progress
 * @returns {undefined}
 */
slideshow.Random.prototype.step = function(progress) {
    return this.effects[this.effect].step(progress);
}


/**
 * The easing functions' namespace.
 *
 * @see http://gizma.com/easing/
 */
slideshow.easing = {}


/**
 * Returns a linear easing value.
 *
 * @param   {Number} progress
 * @returns {Number}
 */
slideshow.easing.linear = function(progress) {
    return progress;
}


/**
 * Returns an ease in easing value.
 *
 * @param   {Number} progress
 * @returns {Number}
 */
slideshow.easing.easeIn = function(progress) {
    return progress * progress;
}


/**
 * Returns an ease out easing value.
 *
 * @param   {Number} progress
 * @returns {Number}
 */
slideshow.easing.easeOut = function(progress) {
    return - progress * (progress - 2);
}


/**
 * Returns an ease in-out easing value.
 *
 * @param   {Number} progress
 * @returns {Number}
 */
slideshow.easing.easeInOut = function(progress) {
    progress *= 2;
    if (progress < 1) {
        return progress * progress / 2;
    } else {
        progress--;
        return -1/2 * (progress * (progress - 2) - 1);
    }
}
