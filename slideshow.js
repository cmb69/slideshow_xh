// $Id$

/**
 * JS of Slideshow_XH.
 *
 * @copyright Copyright (c) 2012 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl.html GPLv3
 */


// https://hacks.mozilla.org/2011/08/animating-with-javascript-from-setinterval-to-requestanimationframe/


/**
 * The namespace.
 */
var slideshow = {}


slideshow.FRAME_DURATION = 1000 / 50;


slideshow.MAX_DELTA_T = 10 * slideshow.FRAME_DURATION;


slideshow.RAF = window.mozRequestAnimationFrame
    || window.webkitRequestAnimationFrame
    || window.msRequestAnimationFrame
    || window.oRequestAnimationFrame;


/**
 * Main slideshow class.
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


slideshow.Show.prototype.getPrevious = function() {
    return this.current.previousSibling
        ? this.current.previousSibling
        : this.current.parentNode.lastChild;
}


slideshow.Show.prototype.next = function() {
    this.current = this.current.nextSibling
        ? this.current.nextSibling
        : this.current.parentNode.firstChild;
    return this.current;
}


slideshow.Show.prototype.init = function() {
    var style;

    style = this.current.style;
    style.zIndex = 2;
    style.display = "block";
    this.effect.prepare();
}


slideshow.Show.prototype.animate = function() {
    var that, now, deltaT;

    that = this;
    if (this.lastFrame === null) {
        this.lastFrame = new Date().getTime();
    }
    now = new Date().getTime();
    if (this.running < 1) {
        if (slideshow.RAF) {
            slideshow.RAF.call(window, function() {that.animate();}, this.elt);
        } else {
            setTimeout(function() {that.animate()}, slideshow.FRAME_DURATION);
        }
        deltaT = now - this.lastFrame;
        if (deltaT < slideshow.MAX_DELTA_T) {
            this.render(deltaT);
        }
        this.lastFrame = now;
    } else {
        this.lastFrame = null;
        this.running = 0;
        setTimeout(function() {that.animate()}, this.pause)
    }
}


slideshow.Show.prototype.render = function(deltaT) {
    var img, prev;

    img = this.current;

    this.running += deltaT / this.duration;
    this.running = Math.min(this.running, 1);

    this.effect.step(this.easing(this.running));

    if (this.running >= 1) {
        prev = this.getPrevious();
        prev.style.zIndex = 0;
        prev.style.display = "none";
        img.style.zIndex = 1;
        img = this.next();
        img.style.zIndex = 2;
        img.style.display = "block";
        this.effect.prepare();
    }
}


/**
 * The 'fade' effect.
 */
slideshow.Fader = function(show) {
    this.show = show;
}


slideshow.Fader.setOpacity = function(img, val) {
    if (typeof img.style.opacity != 'undefined') {
        img.style.opacity = val;
    } else {
        img.style.filter = "alpha(opacity = " + Math.round(100 * val) + ")";
    }
}


slideshow.Fader.prototype.prepare = function() {
    slideshow.Fader.setOpacity(this.show.current, 0);
}


slideshow.Fader.prototype.step = function(progress) {
    slideshow.Fader.setOpacity(this.show.current, progress);
}


/**
 * The 'slide' effect.
 */
slideshow.Slider = function(show) {
    this.show = show;
}


slideshow.Slider.prototype.prepare = function() {
    var img;

    this.show.getPrevious().style.left = "0px";
    img = this.show.current;
    img.style.left = - img.width + "px";
}


slideshow.Slider.prototype.step = function(progress) {
    var img;

    img = this.show.getPrevious();
    img.style.left = progress * img.width + "px";
    img = this.show.current;
    img.style.left = progress * img.width - img.width + "px";
}


/**
 * The 'curtain' effect.
 */
slideshow.Curtain = function(show) {
    this.show = show;
}


slideshow.Curtain.prototype.prepare = function() {
    var img;

    img = this.show.current;
    img.style.top = -img.height + "px";
}


slideshow.Curtain.prototype.step = function(progress) {
    var img;

    img = this.show.current;
    img.style.top = (progress - 1) * img.height + "px";
}


/**
 * The 'random' effect.
 *
 * Switch randomly between all available effects.
 */
slideshow.Random = function(show) {
    this.show = show;
    this.effects = [new slideshow.Fader(show),
                    new slideshow.Slider(show),
                    new slideshow.Curtain(show)];
    this.effect = -1;
}

slideshow.Random.prototype.prepare = function() {
    this.effect = Math.floor(3 * Math.random());
    this.effects[this.effect].prepare();
}

slideshow.Random.prototype.step = function(progress) {
    return this.effects[this.effect].step(progress);
}


/*
 * The easing functions.
 *
 * @see http://gizma.com/easing/
 */
slideshow.easing = {}


slideshow.easing.linear = function(progress) {
    return progress;
}


slideshow.easing.easeIn = function(progress) {
    return progress * progress;
}


slideshow.easing.easeOut = function(progress) {
    return - progress * (progress - 2);
}


slideshow.easing.easeInOut = function(progress) {
    progress *= 2;
    if (progress < 1) {
        return progress * progress / 2;
    } else {
        progress--;
        return -1/2 * (progress * (progress - 2) - 1);
    }
}
