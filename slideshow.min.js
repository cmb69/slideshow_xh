/**
 * Copyright (c) Christoph M. Becker
 *
 * This file is part of Slideshow_XH.
 *
 * Slideshow_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Slideshow_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Slideshow_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

// @ts-check

/**
 * The interval in milliseconds between two animation frames.
 */
var FRAME_DURATION = 1000 / 50;


/**
 * The time in milliseconds,
 * which triggers the rendering of a new animation frame.
 */
var MAX_DELTA_T = 10 * FRAME_DURATION;


/**
 * Main slideshow class.
 *
 * @constructor
 *
 * @param {HTMLElement} element
 * @param {String} effect
 * @param {String} easing
 * @param {Number} delay
 * @param {Number} pause
 * @param {Number} duration
 */
var Show = function(element, effect, easing, delay, pause, duration) {
    this.elt = element;
    switch (effect) {
    case 'fade':
        this.effect = new Fader(this);
        break;
    case 'slide':
        this.effect = new Slider(this);
        break;
    case 'curtain':
        this.effect = new Curtain(this);
        break;
    default:
        this.effect = new Random(this);
        break;
    }
    this.easing = easings[easing] ? easings[easing] : easings.easeInOut;
    this.pause = pause;
    this.duration = duration;

    this.pictures = this.elt.getElementsByTagName("picture");
    this.currentIndex = 1;
    this.current = this.pictures[this.currentIndex];
    this.init();
    this.running = 0;
    this.lastFrame = null;
    setTimeout(this.animate.bind(this), delay + pause);
};


/**
 * Returns the previous `image' element.
 *
 * @returns {HTMLPictureElement}
 */
Show.prototype.getPrevious = function() {
    var i = this.currentIndex - 1;
    
    if (i < 0) {
        i += this.pictures.length - 1;
    }
    return this.pictures[i];
};


/**
 * Returns the next `image' element.
 *
 * @returns {HTMLPictureElement}
 */
Show.prototype.next = function() {
    this.currentIndex++;
    if (this.currentIndex >= this.pictures.length - 1) {
        this.currentIndex = 0;
    }
    this.current = this.pictures[this.currentIndex];
    return this.current;
};


/**
 * Initializes the slideshow.
 *
 * @returns {void}
 */
Show.prototype.init = function() {
    // Insert a clone of the first image with static position
    // to force height of the surrounding `div' to be greater than 0,
    // before absolutely positioning the first image.
    var clone = /** @type {HTMLPictureElement} */ (this.pictures[0].cloneNode(true));
    var style = clone.style;
    //style.position = "static";
    style.visibility = "hidden";
    this.elt.appendChild(clone);
    this.pictures[0].style.position = "absolute";

    style = this.current.style;
    style.display = "block";
    this.effect.prepare();
};


/**
 * Executes the next animation step.
 *
 * @returns {void}
 */
Show.prototype.animate = function() {
    var now, deltaT;

    if (this.lastFrame === null) {
        this.lastFrame = new Date().getTime();
    }
    now = new Date().getTime();
    if (this.running < 1) {
        if (window.requestAnimationFrame) {
            window.requestAnimationFrame(this.animate.bind(this));
        }
        deltaT = now - this.lastFrame;
        if (deltaT < MAX_DELTA_T) {
            this.render(deltaT);
        }
        this.lastFrame = now;
    } else {
        this.lastFrame = null;
        this.running = 0;
        setTimeout(this.animate.bind(this), this.pause);
    }
};


/**
 * Renders the animation.
 *
 * @param   {Number} deltaT
 * @returns {void}
 */
Show.prototype.render = function(deltaT) {
    var img, prev;

    img = this.current;
    img.style.zIndex = "2";

    this.running += deltaT / this.duration;
    this.running = Math.min(this.running, 1);

    this.effect.step(this.easing(this.running));

    if (this.running >= 1) {
        prev = this.getPrevious();
        prev.style.zIndex = "0";
        prev.style.display = "none";
        img.style.zIndex = "1";
        img = this.next();
        img.style.display = "block";
        this.effect.prepare();
    }
};


/**
 * The `fade' effect class.
 *
 * @constructor
 *
 * @param {Show} show
 */
var Fader = function(show) {
    this.show = show;
};


/**
 * Prepares the next image.
 *
 * @public
 *
 * @returns {void}
 */
Fader.prototype.prepare = function() {
    this.show.current.style.opacity = "0";
};


/**
 * Executes the next animation step.
 *
 * @public
 *
 * @param   {Number} progress
 * @returns {void}
 */
Fader.prototype.step = function(progress) {
    this.show.current.style.opacity = progress.toString();
};


/**
 * The `slide' effect class.
 *
 * @constructor
 *
 * @param {Show} show
 */
var Slider = function(show) {
    this.show = show;
};


/**
 * Prepares the next image.
 *
 * @public
 *
 * @returns {void}
 */
Slider.prototype.prepare = function() {
    var img;

    this.show.getPrevious().style.left = "0px";
    img = this.show.current;
    img.style.left = - img.offsetWidth + "px";
};


/**
 * Executes the next animation step.
 *
 * @public
 *
 * @param   {Number} progress
 * @returns {void}
 */
Slider.prototype.step = function(progress) {
    var img;

    img = this.show.getPrevious();
    img.style.left = progress * img.offsetWidth + "px";
    img = this.show.current;
    img.style.left = (progress - 1) * img.offsetWidth + "px";
};


/**
 * The `curtain' effect class.
 *
 * @constructor
 *
 * @param {Show} show
 */
var Curtain = function(show) {
    this.show = show;
};


/**
 * Prepares the next image.
 *
 * @public
 *
 * @returns {void}
 */
Curtain.prototype.prepare = function() {
    var img;

    img = this.show.current;
    img.style.top = -img.offsetHeight + "px";
};


/**
 * Executes the next animation step.
 *
 * @public
 *
 * @param   {Number} progress
 * @returns {void}
 */
Curtain.prototype.step = function(progress) {
    var img;

    img = this.show.current;
    img.style.top = (progress - 1) * img.offsetHeight + "px";
};


/**
 * The `random' effect.
 *
 * Switch randomly between all available effects.
 *
 * @constructor
 *
 * @param   {Show} show
 * @returns {void}
 */
var Random = function(show) {
    this.show = show;
    this.effects = [new Fader(show),
                    new Slider(show),
                    new Curtain(show)];
    this.effect = -1;
};

/**
 * Prepares the next image.
 *
 * @public
 *
 * @returns {void}
 */
Random.prototype.prepare = function() {
    this.effect = Math.floor(3 * Math.random());
    this.effects[this.effect].prepare();
};

/**
 * Executes the next animation step.
 *
 * @public
 *
 * @param   {Number} progress
 * @returns {void}
 */
Random.prototype.step = function(progress) {
    return this.effects[this.effect].step(progress);
};


/**
 * The easing functions' namespace.
 *
 * @see http://gizma.com/easing/
 */
var easings = {};


/**
 * Returns a linear easing value.
 *
 * @param   {Number} progress
 * @returns {Number}
 */
easings.linear = function(progress) {
    return progress;
};


/**
 * Returns an ease in easing value.
 *
 * @param   {Number} progress
 * @returns {Number}
 */
easings.easeIn = function(progress) {
    return progress * progress;
};


/**
 * Returns an ease out easing value.
 *
 * @param   {Number} progress
 * @returns {Number}
 */
easings.easeOut = function(progress) {
    return - progress * (progress - 2);
};


/**
 * Returns an ease in-out easing value.
 *
 * @param   {Number} progress
 * @returns {Number}
 */
easings.easeInOut = function(progress) {
    progress *= 2;
    if (progress < 1) {
        return progress * progress / 2;
    } else {
        progress--;
        return -1/2 * (progress * (progress - 2) - 1);
    }
};

(function () {
    /** @type {NodeListOf<HTMLElement>} */
    var slideshows = document.querySelectorAll('.slideshow');
    for (var i = 0; i < slideshows.length; i++) {
        var show = slideshows[i];
        new Show(
            show,
            show.getAttribute('data-effect') || "",
            show.getAttribute('data-easing') || "",
            +(show.getAttribute('data-delay') || ""),
            +(show.getAttribute('data-pause') || ""),
            +(show.getAttribute('data-duration') || "")
        );
    }
})();
