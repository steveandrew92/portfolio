import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function readThemeRoles() {
    const el = document.getElementById('portfolio-theme-data');
    if (!el) return {};
    try {
        return JSON.parse(el.textContent || '{}');
    } catch {
        return {};
    }
}

function setActiveRole(roleKey) {
    document.documentElement.dataset.role = roleKey;
    const app = document.getElementById('portfolio-app');
    if (app) app.dataset.activeRole = roleKey;

    document.querySelectorAll('[data-role-card]').forEach((card) => {
        card.classList.toggle('is-active', card.dataset.roleCard === roleKey);
    });

    document.querySelectorAll('[data-role-nav]').forEach((link) => {
        link.classList.toggle('text-accent', link.dataset.roleNav === roleKey);
        link.classList.toggle('font-semibold', link.dataset.roleNav === roleKey);
    });
}

function initThemeToggle() {
    const btn = document.getElementById('theme-toggle');
    if (!btn) return;

    const stored = localStorage.getItem('sa-theme');
    if (stored === 'light' || stored === 'dark') {
        document.documentElement.dataset.theme = stored;
    }

    btn.addEventListener('click', () => {
        const next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
        document.documentElement.dataset.theme = next;
        localStorage.setItem('sa-theme', next);
    });
}

function initRoleCards() {
    const panels = document.querySelectorAll('[data-role-panel]');
    const cards = document.querySelectorAll('[data-role-card]');

    const openPanel = (roleKey) => {
        setActiveRole(roleKey);
        panels.forEach((panel) => {
            const isTarget = panel.dataset.rolePanel === roleKey;
            panel.classList.toggle('is-open', isTarget);
            panel.classList.toggle('hidden', !isTarget);
            panel.setAttribute('aria-hidden', isTarget ? 'false' : 'true');
        });
        cards.forEach((card) => {
            card.setAttribute('aria-expanded', card.dataset.roleCard === roleKey ? 'true' : 'false');
        });

        const target = document.getElementById(`panel-${roleKey}`);
        if (target) {
            requestAnimationFrame(() => {
                target.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth', block: 'start' });
            });
        }

        if (!prefersReducedMotion) {
            gsap.fromTo(
                `#panel-${roleKey} .reveal-panel`,
                { opacity: 0, y: 24 },
                { opacity: 1, y: 0, duration: 0.6, stagger: 0.08, ease: 'power3.out' },
            );
        }
    };

    const closePanels = () => {
        panels.forEach((panel) => {
            panel.classList.remove('is-open');
            panel.classList.add('hidden');
            panel.setAttribute('aria-hidden', 'true');
        });
        cards.forEach((card) => card.setAttribute('aria-expanded', 'false'));
    };

    cards.forEach((card) => {
        const roleKey = card.dataset.roleCard;
        card.addEventListener('click', () => openPanel(roleKey));
        card.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openPanel(roleKey);
            }
        });
        card.addEventListener('mouseenter', () => setActiveRole(roleKey));
    });

    document.querySelectorAll('.role-panel-close').forEach((btn) => {
        btn.addEventListener('click', () => {
            closePanels();
            document.getElementById('roles')?.scrollIntoView({ behavior: 'smooth' });
        });
    });
}

function initScrollTheme() {
    if (prefersReducedMotion) return;

    document.querySelectorAll('[data-role-card]').forEach((card) => {
        ScrollTrigger.create({
            trigger: card,
            start: 'top 70%',
            end: 'bottom 30%',
            onEnter: () => setActiveRole(card.dataset.roleCard),
            onEnterBack: () => setActiveRole(card.dataset.roleCard),
        });
    });
}

function initReveals() {
    if (prefersReducedMotion) {
        gsap.set('.reveal, .reveal-panel', { opacity: 1, y: 0 });
        return;
    }

    gsap.utils.toArray('.reveal').forEach((el) => {
        gsap.from(el, {
            scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: 'play none none reverse' },
            opacity: 0,
            y: 32,
            duration: 0.8,
            ease: 'power3.out',
        });
    });
}

function initParallax() {
    if (prefersReducedMotion) return;

    const orb = document.getElementById('parallax-orb');
    const glowA = document.getElementById('bg-glow-a');
    const glowB = document.getElementById('bg-glow-b');

    if (orb) {
        gsap.to(orb, {
            y: 120,
            ease: 'none',
            scrollTrigger: { trigger: document.body, start: 'top top', end: 'bottom bottom', scrub: 1.2 },
        });
    }
    if (glowA) {
        gsap.to(glowA, {
            y: -80,
            x: 40,
            ease: 'none',
            scrollTrigger: { trigger: document.body, start: 'top top', end: 'max', scrub: 0.8 },
        });
    }
    if (glowB) {
        gsap.to(glowB, {
            y: 60,
            x: -30,
            ease: 'none',
            scrollTrigger: { trigger: document.body, start: 'top top', end: 'max', scrub: 1 },
        });
    }
}

function initMagneticButtons() {
    if (prefersReducedMotion) return;

    document.querySelectorAll('[data-magnetic]').forEach((el) => {
        const strength = 0.35;
        el.addEventListener('mousemove', (e) => {
            const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            gsap.to(el, { x: x * strength, y: y * strength, duration: 0.35, ease: 'power2.out' });
        });
        el.addEventListener('mouseleave', () => {
            gsap.to(el, { x: 0, y: 0, duration: 0.5, ease: 'elastic.out(1, 0.5)' });
        });
    });
}

function initCaseStudyModal() {
    const modal = document.getElementById('case-study-modal');
    if (!modal) return;

    const backdrop = modal.querySelector('.case-study-backdrop');
    const closeBtn = modal.querySelector('.case-study-close');
    const titleEl = document.getElementById('cs-title');
    const roleEl = document.getElementById('cs-role');
    const bodyEl = document.getElementById('cs-body');
    const linkEl = document.getElementById('cs-link');

    const open = (project, roleTitle) => {
        roleEl.textContent = roleTitle;
        titleEl.textContent = project.title;
        const cs = project.case_study || {};
        bodyEl.innerHTML = `
            <div><strong class="text-ink">Overview</strong><p class="mt-1">${cs.overview || ''}</p></div>
            <div><strong class="text-ink">Challenges</strong><p class="mt-1">${cs.challenges || ''}</p></div>
            <div><strong class="text-ink">Outcomes</strong><p class="mt-1">${cs.outcomes || ''}</p></div>
        `;
        if (cs.link) {
            linkEl.href = cs.link;
            linkEl.classList.remove('hidden');
        } else {
            linkEl.classList.add('hidden');
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        if (!prefersReducedMotion) {
            gsap.fromTo(modal.querySelector('.case-study-dialog'), { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 0.45, ease: 'power3.out' });
        }
    };

    const close = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    };

    document.querySelectorAll('.case-study-open').forEach((btn) => {
        btn.addEventListener('click', () => {
            const project = JSON.parse(btn.dataset.project || '{}');
            open(project, btn.dataset.roleTitle || '');
        });
    });
    backdrop?.addEventListener('click', close);
    closeBtn?.addEventListener('click', close);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
    });
}

export function initPortfolio() {
    const roles = readThemeRoles();
    const firstRole = Object.keys(roles)[0] || 'system_analyst';
    document.documentElement.dataset.role = document.documentElement.dataset.role || firstRole;

    initThemeToggle();
    initRoleCards();
    initScrollTheme();
    initReveals();
    initParallax();
    initMagneticButtons();
    initCaseStudyModal();

    setActiveRole(document.getElementById('portfolio-app')?.dataset.activeRole || firstRole);
}
