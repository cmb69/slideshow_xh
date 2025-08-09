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

/* jshint browser:true,strict:implied */
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
 * @param {string} effect
 * @param {string} easing
 * @param {number} delay
 * @param {number} pause
 * @param {number} duration
 */
var Show = function(element, effect, easing, delay, pause, duration) {
    this.elt = element;
    switch (effect) {
    case 'fade':
        this.effect = fader(this);
        break;
    case 'slide':
        this.effect = slider(this);
        break;
    case 'curtain':
        this.effect = curtain(this);
        break;
    default:
        this.effect = random(this);
        break;
    }
    this.easing = easingByName(easing);
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
 * @param   {number} deltaT
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


/** @param {Show} show */
function fader(show) {
    return {
        prepare: prepare,
        step: step
    };

    function prepare() {
        show.current.style.opacity = "0";
    }

    /** @param {number} progress */
    function step(progress) {
        show.current.style.opacity = progress.toString();
    }
}

/** @param {Show} show */
function slider(show) {
    return {
        prepare: prepare,
        step: step
    };

    function prepare() {
        var img;

        show.getPrevious().style.left = "0px";
        img = show.current;
        img.style.left = - img.offsetWidth + "px";
    }

    /** @param {number} progress */
    function step(progress) {
        var img;

        img = show.getPrevious();
        img.style.left = progress * img.offsetWidth + "px";
        img = show.current;
        img.style.left = (progress - 1) * img.offsetWidth + "px";
    }
}

/** @param {Show} show */
function curtain(show) {
    return {
        prepare: prepare,
        step: step
    };

    function prepare() {
        var img;

        img = show.current;
        img.style.top = -img.offsetHeight + "px";
    }

    /** @param {number} progress */
    function step(progress) {
        var img;

        img = show.current;
        img.style.top = (progress - 1) * img.offsetHeight + "px";
    }
}

/** @param {Show} show */
function random(show) {
    var effects = [fader(show),
                    slider(show),
                    curtain(show)];
    var effect = -1;

    return {
        prepare: prepare,
        step: step
    };

    function prepare() {
        effect = Math.floor(3 * Math.random());
        effects[effect].prepare();
    }

    /** @param {number} progress */
    function step(progress) {
        return effects[effect].step(progress);
    }
}

/**
 * @see http://gizma.com/easing/
 * @param {string} name
 */
function easingByName(name) {
    return {
        linear: linear,
        easeIn: easeIn,
        easeOut: easeOut,
        easeInOut: easeInOut
    }[name] || easeInOut;

    /** @param {number} progress */
    function linear(progress) {
        return progress;
    }

    /** @param {number} progress */
    function easeIn(progress) {
        return progress * progress;
    }

    /** @param {number} progress */
    function easeOut(progress) {
        return - progress * (progress - 2);
    }

    /** @param {number} progress */
    function easeInOut(progress) {
        progress *= 2;
        if (progress < 1) {
            return progress * progress / 2;
        } else {
            progress--;
            return -1/2 * (progress * (progress - 2) - 1);
        }
    }
}


/** @type {NodeListOf<HTMLElement>} */ (document.querySelectorAll('.slideshow')).forEach(function (show) {
    new Show(
        show,
        show.getAttribute('data-effect') || "",
        show.getAttribute('data-easing') || "",
        +(show.getAttribute('data-delay') || ""),
        +(show.getAttribute('data-pause') || ""),
        +(show.getAttribute('data-duration') || "")
    );
});
