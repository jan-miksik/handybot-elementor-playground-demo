(() => {
  const button = document.querySelector('.hb-menu-button');
  const menu = document.querySelector('.hb-site-nav');
  if (button && menu) {
    button.addEventListener('click', () => {
      const isOpen = menu.classList.toggle('is-open');
      button.setAttribute('aria-expanded', String(isOpen));
      button.textContent = isOpen ? '×' : '☰';
    });

    menu.addEventListener('click', (event) => {
      if (!event.target.closest('a')) return;
      menu.classList.remove('is-open');
      button.setAttribute('aria-expanded', 'false');
      button.textContent = '☰';
    });
  }

  const money = new Intl.NumberFormat('cs-CZ', {
    style: 'currency',
    currency: 'CZK',
    maximumFractionDigits: 0,
  });

  document.querySelectorAll('[data-hb-roi]').forEach((calculator) => {
    const getInput = (name) => calculator.querySelector(`[data-roi-input="${name}"]`);
    const getOutput = (name) => calculator.querySelector(`[data-roi-output="${name}"]`);
    const calculate = () => {
      const people = Math.max(0, Number(getInput('people')?.value) || 0);
      const hours = Math.max(0, Number(getInput('hours')?.value) || 0);
      const rate = Math.max(0, Number(getInput('rate')?.value) || 0);
      const savedHours = people * hours * 0.7;
      const monthly = savedHours * rate;

      getOutput('monthly').textContent = money.format(monthly);
      getOutput('yearly').textContent = money.format(monthly * 12);
      getOutput('hours').textContent = `${Math.round(savedHours).toLocaleString('cs-CZ')} hodin práce`;
    };

    calculator.querySelectorAll('[data-roi-input]').forEach((input) => {
      input.addEventListener('input', calculate);
    });
    calculate();
  });
})();
