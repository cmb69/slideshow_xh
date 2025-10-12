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

/**
 * @typedef {{getCurrent: () => HTMLPictureElement;getPrevious: () => HTMLPictureElement;}} Show
 * @typedef {{prepare: () => void;step: (progress: number) => void;}} Effect
 */

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
 * @param {HTMLElement} element
 * @param {string} effect
 * @param {string} easingName
 * @param {number} delay
 * @param {number} pause
 * @param {number} duration
 */
function widget(element, effect, easingName, delay, pause, duration) {
    var /** @type {Show} */ show,
        /** @type {Effect} */ effectF,
        /** @type {(progress: number) => number} */ easing,
        /** @type {HTMLPictureElement[]} */ pictures,
        /** @type {number} */ currentIndex,
        /** @type {HTMLPictureElement} */ current,
        /** @type {number} */ running,
        /** @type {?number} */ lastFrame;

    show = {
        getCurrent: getCurrent,
        getPrevious: getPrevious,
    };
    switch (effect) {
        case "fade":
            effectF = fader(show);
            break;
        case "slide":
            effectF = slider(show);
            break;
        case "curtain":
            effectF = curtain(show);
            break;
        default:
            effectF = random(show);
            break;
    }
    easing = easingByName(easingName);

    pictures = Array.prototype.slice.call(element.getElementsByTagName("picture"));
    currentIndex = 1;
    current = pictures[currentIndex];
    init();
    running = 0;
    lastFrame = null;
    setTimeout(animate, delay + pause);

    function getCurrent() {
        return current;
    }

    /** @return {HTMLPictureElement} */
    function getPrevious() {
        var /** @type {?number} */ i;

        i = currentIndex - 1;
        if (i < 0) {
            i += pictures.length - 1;
        }
        return pictures[i];
    }

    /** @return {HTMLPictureElement} */
    function next() {
        currentIndex = (currentIndex + 1) % (pictures.length - 1);
        current = pictures[currentIndex];
        return current;
    }

    function init() {
        var /** @type {HTMLPictureElement} */ clone, /** @type {CSSStyleDeclaration} */ style;

        // Insert a clone of the first image with static position
        // to force height of the surrounding `div' to be greater than 0,
        // before absolutely positioning the first image.
        clone = /** @type {HTMLPictureElement} */ (pictures[0].cloneNode(true));
        style = clone.style;
        //style.position = "static";
        style.visibility = "hidden";
        element.appendChild(clone);
        pictures[0].style.position = "absolute";

        style = current.style;
        style.display = "block";
        effectF.prepare();
    }

    function animate() {
        var /** @type {number} */ now, /** @type {number} */ deltaT;

        if (lastFrame === null) {
            lastFrame = new Date().getTime();
        }
        now = new Date().getTime();
        if (running < 1) {
            if (window.requestAnimationFrame) {
                window.requestAnimationFrame(animate);
            }
            deltaT = now - lastFrame;
            if (deltaT < MAX_DELTA_T) {
                render(deltaT);
            }
            lastFrame = now;
        } else {
            lastFrame = null;
            running = 0;
            setTimeout(animate, pause);
        }
    }

    /**
     * Renders the animation.
     *
     * @param   {number} deltaT
     * @returns {void}
     */
    function render(deltaT) {
        var /** @type {HTMLPictureElement} */ img, /** @type {HTMLPictureElement} */ prev;

        img = current;
        img.style.zIndex = "2";

        running += deltaT / duration;
        running = Math.min(running, 1);

        effectF.step(easing(running));

        if (running >= 1) {
            prev = getPrevious();
            prev.style.zIndex = "0";
            prev.style.display = "none";
            img.style.zIndex = "1";
            img = next();
            img.style.display = "block";
            effectF.prepare();
        }
    }
}

/** @param {Show} show */
function fader(show) {
    return {
        prepare: prepare,
        step: step,
    };

    function prepare() {
        show.getCurrent().style.opacity = "0";
    }

    /** @param {number} progress */
    function step(progress) {
        show.getCurrent().style.opacity = progress.toString();
    }
}

/** @param {Show} show */
function slider(show) {
    return {
        prepare: prepare,
        step: step,
    };

    function prepare() {
        var /** @type {HTMLPictureElement} */ img;

        show.getPrevious().style.left = "0px";
        img = show.getCurrent();
        img.style.left = -img.offsetWidth + "px";
    }

    /** @param {number} progress */
    function step(progress) {
        var /** @type {HTMLPictureElement} */ img;

        img = show.getPrevious();
        img.style.left = progress * img.offsetWidth + "px";
        img = show.getCurrent();
        img.style.left = (progress - 1) * img.offsetWidth + "px";
    }
}

/** @param {Show} show */
function curtain(show) {
    return {
        prepare: prepare,
        step: step,
    };

    function prepare() {
        var /** @type {HTMLPictureElement} */ img;

        img = show.getCurrent();
        img.style.top = -img.offsetHeight + "px";
    }

    /** @param {number} progress */
    function step(progress) {
        var /** @type {HTMLPictureElement} */ img;

        img = show.getCurrent();
        img.style.top = (progress - 1) * img.offsetHeight + "px";
    }
}

/** @param {Show} show */
function random(show) {
    var /** @type {Effect[]} */ effects, /** @type {number} */ effect;

    effects = [fader(show), slider(show), curtain(show)];
    effect = -1;

    return {
        prepare: prepare,
        step: step,
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
 * @return {(progress: number) => number}
 */
function easingByName(name) {
    return (
        {
            linear: linear,
            easeIn: easeIn,
            easeOut: easeOut,
            easeInOut: easeInOut,
        }[name] || easeInOut
    );

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
        return -progress * (progress - 2);
    }

    /** @param {number} progress */
    function easeInOut(progress) {
        progress *= 2;
        if (progress < 1) {
            return (progress * progress) / 2;
        } else {
            progress--;
            return (-1 / 2) * (progress * (progress - 2) - 1);
        }
    }
}

/** @type {NodeListOf<HTMLElement>} */ (document.querySelectorAll(".slideshow")).forEach(
    function (show) {
        widget(
            show,
            show.getAttribute("data-effect") || "",
            show.getAttribute("data-easing") || "",
            +(show.getAttribute("data-delay") || ""),
            +(show.getAttribute("data-pause") || ""),
            +(show.getAttribute("data-duration") || "")
        );
    }
);
