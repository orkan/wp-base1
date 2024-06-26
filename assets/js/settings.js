/*
 * This file is part of the orkan/wp-base1 package.
 * Copyright (c) 2024 Orkan <orkans+wpbase1@gmail.com>
 */
/* DO NOT EDIT - AUTO-GENERATED FROM: wp-content/plugins/ork-base1/assets/js/src/settings.js */
window.orkbasex1 = window.orkbasex1 || {};

/*
 * ==============================================================================================
 * Ork | Functions
 */
orkbasex1.fn = {
  /**
   * Get element ID from name.
   */
  getId(name) {
    return '#' + orkbasex1.name2id[name];
  },

  /**
   * Get element from name.
   */
  getElement(name) {
    return document.getElementById(orkbasex1.name2id[name] ?? null);
  },

  /**
   * Logger.
   */
  log(msg, type = 'log') {
    if ('debug' === type && !orkbasex1.debug) {
      return;
    } else {
      type = 'log';
    }

    console[type](msg);
  },

  debug(msg) {
    this.log(msg, 'debug');
  },

  /**
   * Clear FORM inputs.
   */
  clearForm(form) {
    form.reset();
    Array.from(form.elements).forEach((input) => {
      if (input.disabled) {
        return;
      }
      switch (input.type.toLowerCase()) {
        case 'text':
        case 'password':
        case 'textarea':
        case 'hidden':
          input.value = '';
          break;

        case 'radio':
        case 'checkbox':
          input.checked = false;
          break;

        case 'select-one':
        case 'select-multi':
          input.selectedIndex = -1;
          break;
      }
    });
  },

  /**
   * Must be called as: await usleep(1); from within another async function! :(
   */
  async usleep(usec) {
    return await new Promise((resolve, reject) => setTimeout(() => resolve(1), usec));
  },
};

window.orkbasex1 = window.orkbasex1 || {};

/*
 * ==============================================================================================
 * Ork | Ajax
 */
orkbasex1.Ajax = {
  /**
   * Ajax request
   */
  async fetch(opts = {}, data = {}) {
    let json,
      status,
      url = orkbasex1.url;

    data.action = opts.action;
    data[orkbasex1.nonce.name] = orkbasex1.nonce.action;

    /*
     * Deaults:
     *
     * [signal]
     * See AbortController::signal
     *
     * [Spinner]
     * DOM element to toggle class from opts[spinCls]
     */
    opts = {
      ...{
        method: 'POST',
        signal: null,
        Spinner: null,
        spinCls: null,
      },
      ...opts,
    };

    switch (opts.method) {
      case 'GET':
        url += '?' + this.getHttpQuery(data);
        break;
      case 'POST':
        opts.body = this.getFormData(data);
        break;
    }

    this.showSpinner(true, opts.Spinner, opts.spinCls);

    const Response = await fetch(url, opts)
      .then((Response) => {
        status = `[${Response.status}] ${Response.statusText}`;
        orkbasex1.fn.debug(Response);
        return Response.text();
      })
      .catch((E) => {
        throw new Error(E); // Connection errors
      });

    this.showSpinner(false, opts.Spinner, opts.spinCls);

    try {
      json = JSON.parse(Response);
    } catch (E) {
      throw new Error(E); // Data errors
    }

    if (!json || !json.success) {
      throw new Error(json.data || status); // WP errors
    }

    return json.data;
  },

  /**
   * Build query string from object.
   */
  getHttpQuery(obj) {
    const Query = new URLSearchParams(obj);
    return Query.toString();
  },

  /**
   * Convert Object to FormData instance.
   */
  getFormData(obj) {
    const Data = new FormData();
    for (const [name, value] of Object.entries(obj)) {
      Data.append(name, value);
    }
    return Data;
  },

  /**
   * Show/hide spinner
   */
  showSpinner(show, Spinner, className = null) {
    if (Spinner) {
      className = className ?? 'is-active';
      const ClassList = Spinner.classList;
      show ? ClassList.add(className) : ClassList.remove(className);
    }
  },
};

