/*
 * This file is part of the orkan/wp-base1 package.
 * Copyright (c) 2024 Orkan <orkans+wpbase1@gmail.com>
 */
/* DO NOT EDIT - AUTO-GENERATED FROM: wp-content/plugins/ork-base1/assets/js/src/tool-ajaxer.js */
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


(function ($) {
  /*
   * ---------------------------------------------------------------------------------------------------------
   * onLoad
   */
  $(() => {
    let Controller;
    // [selector] Handle onChange event on <select>
    $(orkbasex1.fn.getId('selector_select'))
      .on('change', async (Event) => {
        const name = Event.currentTarget.value;
        const Result = orkbasex1.fn.getElement('selector_result');
        Result.value = '---';

        if (name) {
          try {
            // Abort previous Request if in progress (race condition)
            Controller && Controller.abort();
            Controller = new AbortController();

            // Ajax request
            Result.value = orkbasex1.l10n.wait;
            Result.value = await orkbasex1.Ajax.fetch(
              {
                action: orkbasex1.actions.ajaxer.selector,
                signal: Controller.signal,
                Spinner: document.getElementById('selector_spin'),
              },
              { name }
            );

            Controller = null;
          } catch (E) {
            Result.value = 'Error: ' + E.message;
            throw E;
          }
        }
      })
      .change();
  });

  $(() => {
    let Controller;
    // [texter] Handle onInput event on <text>
    $(orkbasex1.fn.getId('texter_text')).on('input', async (Event) => {
      const text = Event.currentTarget.value;
      const error = orkbasex1.fn.getElement('texter_error').checked;
      const Result = orkbasex1.fn.getElement('texter_result');
      try {
        // Abort previous Request if in progress (race condition)
        Controller && Controller.abort();
        Controller = new AbortController();

        // Ajax request
        Result.value = await orkbasex1.Ajax.fetch(
          {
            action: orkbasex1.actions.ajaxer.echotext,
            signal: Controller.signal,
            Spinner: document.getElementById('texter_spin'),
          },
          { text, error }
        );

        Controller = null;
      } catch (E) {
        Result.value = 'Error: ' + E.message;
        throw E;
      }
    });
  });

  $(() => {
    // [cache] Clear input fields cache
    $(orkbasex1.fn.getId('cache_btn')).on('click', async (Event) => {
      const Div = document.getElementById('cache_result');
      try {
        Event.currentTarget.disabled = true;
        Div.innerHTML = '';
        Div.innerHTML = await orkbasex1.Ajax.fetch({
          action: orkbasex1.actions.ajaxer.flushcache,
          method: 'GET',
          Spinner: document.getElementById('cache_spin'),
        });
        Event.currentTarget.disabled = false;
      } catch (E) {
        Div.innerHTML = 'Error: ' + E.message;
        throw E;
      } finally {
        Event.currentTarget.disabled = false;
      }
    });
  });
})(jQuery);
