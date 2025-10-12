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

(function () {
    "use strict";

    /**
     * @typedef {Object} Show
     * @prop {() => HTMLPictureElement} getCurrent
     * @prop {() => HTMLPictureElement} getPrevious
     */

    /**
     * @typedef {Object} Effect
     * @prop {() => void} prepare
     * @prop {(progress: number) => void} step
     */

    /** @readonly @type {number} */
    var frameDuration = 1000 / 50;

    /** @readonly @type {number} */
    var maxDeltaT = 10 * frameDuration;

    /** @type {<T>(arrayLike: ArrayLike<T>) => T[]} */
    function array(arrayLike) {
        return Array.prototype.slice.call(arrayLike);
    }

    /** @type {(element: HTMLElement) => void} */
    function widget(element) {
        /** @type {number} */
        var pause;
        /** @type {number} */
        var duration;
        /** @type {Effect} */
        var effect;
        /** @type {(progress: number) => number} */
        var easing;
        /** @type {HTMLPictureElement[]} */
        var pictures;
        /** @type {number} */
        var currentIndex;
        /** @type {HTMLPictureElement} */
        var current;
        /** @type {number} */
        var running;
        /** @type {?number} */
        var lastFrame;

        /** @type {() => HTMLPictureElement} */
        function getCurrent() {
            return current;
        }

        /** @type {() => HTMLPictureElement} */
        function getPrevious() {
            var i = currentIndex - 1;
            if (i < 0) {
                i += pictures.length - 1;
            }
            return pictures[i];
        }

        /** @type {() => HTMLPictureElement} */
        function next() {
            currentIndex = (currentIndex + 1) % (pictures.length - 1);
            current = pictures[currentIndex];
            return current;
        }

        /** @type {() => void} */
        function init() {
            // Insert a clone of the first image with static position
            // to force height of the surrounding `div' to be greater than 0,
            // before absolutely positioning the first image.
            var clone = /** @type {HTMLPictureElement} */ (pictures[0].cloneNode(true));
            var style = clone.style;
            //style.position = "static";
            style.visibility = "hidden";
            element.appendChild(clone);
            pictures[0].style.position = "absolute";
            style = current.style;
            style.display = "block";
            effect.prepare();
        }

        /** @type {() => void} */
        function animate() {
            if (lastFrame === null) {
                lastFrame = Date.now();
            }
            var now = Date.now();
            if (running < 1) {
                window.requestAnimationFrame(animate);
                var deltaT = now - lastFrame;
                if (deltaT < maxDeltaT) {
                    render(deltaT);
                }
                lastFrame = now;
            } else {
                lastFrame = null;
                running = 0;
                setTimeout(animate, pause);
            }
        }

        /** @type {(deltaT: number) => void} */
        function render(deltaT) {
            var img = current;
            img.style.zIndex = "2";
            running += deltaT / duration;
            running = Math.min(running, 1);
            effect.step(easing(running));
            if (running >= 1) {
                var prev = getPrevious();
                prev.style.zIndex = "0";
                prev.style.display = "none";
                img.style.zIndex = "1";
                img = next();
                img.style.display = "block";
                effect.prepare();
            }
        }

        (function () {
            var effectName = element.dataset.effect || "";
            var easingName = element.dataset.easing || "";
            var delay = +(element.dataset.delay || "");
            pause = +(element.dataset.pause || "");
            duration = +(element.dataset.duration || "");
            var show = {
                getCurrent: getCurrent,
                getPrevious: getPrevious,
            };
            effect = effectByName(effectName, show);
            easing = easingByName(easingName);
            pictures = array(element.getElementsByTagName("picture"));
            currentIndex = 1;
            current = pictures[currentIndex];
            init();
            running = 0;
            lastFrame = null;
            setTimeout(animate, delay + pause);
        })();
    }

    /** @type {(effectName: string, show: Show) => Effect} */
    function effectByName(effectName, show) {
        switch (effectName) {
            case "fade":
                return fader(show);
            case "slide":
                return slider(show);
            case "curtain":
                return curtain(show);
            default:
                return random(show);
        }
    }

    /** @type {(show: Show) => Effect} */
    function fader(show) {
        /** @type {() => void} */
        function prepare() {
            show.getCurrent().style.opacity = "0";
        }

        /** @type {(progress: number) => void} */
        function step(progress) {
            show.getCurrent().style.opacity = progress.toString();
        }

        return {
            prepare: prepare,
            step: step,
        };
    }

    /** @type {(show: Show) => Effect} */
    function slider(show) {
        /** @type {() => void} */
        function prepare() {
            show.getPrevious().style.left = "0px";
            var img = show.getCurrent();
            img.style.left = -img.offsetWidth + "px";
        }

        /** @type {(progress: number) => void} */
        function step(progress) {
            var img = show.getPrevious();
            img.style.left = progress * img.offsetWidth + "px";
            img = show.getCurrent();
            img.style.left = (progress - 1) * img.offsetWidth + "px";
        }

        return {
            prepare: prepare,
            step: step,
        };
    }

    /** @type {(show: Show) => Effect} */
    function curtain(show) {
        /** @type {() => void} */
        function prepare() {
            var img = show.getCurrent();
            img.style.top = -img.offsetHeight + "px";
        }

        /** @type {(progress: number) => void} */
        function step(progress) {
            var img = show.getCurrent();
            img.style.top = (progress - 1) * img.offsetHeight + "px";
        }

        return {
            prepare: prepare,
            step: step,
        };
    }

    /** @type {(show: Show) => Effect} */
    function random(show) {
        /** @type {Effect[]} */
        var effects;
        /** @type {number} */
        var effect;

        /** @type {() => void} */
        function prepare() {
            effect = Math.floor(3 * Math.random());
            effects[effect].prepare();
        }

        /** @type {(progress: number) => void} */
        function step(progress) {
            return effects[effect].step(progress);
        }

        return (function () {
            effects = [fader(show), slider(show), curtain(show)];
            effect = -1;

            return {
                prepare: prepare,
                step: step,
            };
        })();
    }

    /** @type {(name: string) => (progress: number) => number} */
    function easingByName(name) {
        // see http://gizma.com/easing/

        /** @type {(progress: number) => number} */
        function linear(progress) {
            return progress;
        }

        /** @type {(progress: number) => number} */
        function easeIn(progress) {
            return progress * progress;
        }

        /** @type {(progress: number) => number} */
        function easeOut(progress) {
            return -progress * (progress - 2);
        }

        /** @type {(progress: number) => number} */
        function easeInOut(progress) {
            progress *= 2;
            if (progress < 1) {
                return (progress * progress) / 2;
            } else {
                progress--;
                return (-1 / 2) * (progress * (progress - 2) - 1);
            }
        }

        return (
            {
                linear: linear,
                easeIn: easeIn,
                easeOut: easeOut,
                easeInOut: easeInOut,
            }[name] || easeInOut
        );
    }

    /** @type {HTMLElement[]} */ (array(document.querySelectorAll(".slideshow"))).forEach(widget);
})();
