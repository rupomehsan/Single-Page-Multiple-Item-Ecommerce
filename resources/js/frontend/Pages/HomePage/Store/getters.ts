const DEFAULT_DESC = 'অর্ডার করুন, আমরা পৌঁছে দেব — একদম তাজা ও মজাদার';
const DEFAULT_BADGES = [
    '⏱️ ৩০-৪৫ মিনিটে ডেলিভারি',
    '🚫 কোনো অতিরিক্ত চার্জ নেই',
    '💳 ক্যাশ অন ডেলিভারি',
];

function parseHeroLines(description: string): string[] {
    return description
        .replace(/<br\s*\/?>/gi, '\n')
        .replace(/<\/p>/gi, '\n')
        .replace(/<\/li>/gi, '\n')
        .replace(/<\/div>/gi, '\n')
        .replace(/<[^>]+>/g, '')
        .replace(/&nbsp;/g, ' ')
        .replace(/&amp;/g, '&')
        .split('\n')
        .map((l) => l.trim())
        .filter(Boolean);
}

export const getters = {
    heroDescText(state: any): string {
        if (!state.hero.description) return DEFAULT_DESC;
        const lines = parseHeroLines(state.hero.description);
        return lines[0] || DEFAULT_DESC;
    },

    heroBadges(state: any): string[] {
        if (!state.hero.description) return DEFAULT_BADGES;
        const lines = parseHeroLines(state.hero.description);
        return lines.length > 1 ? lines.slice(1) : DEFAULT_BADGES;
    },

    cartItems(state: any): any[] {
        return state.menu.filter((m: any) => (state.cart[m.id] || 0) > 0);
    },

    cartItemCount(state: any): number {
        return Object.values(state.cart as Record<number, number>).reduce((a, b) => a + b, 0);
    },

    cartTotal(state: any): number {
        return state.menu.reduce(
            (sum: number, item: any) => sum + (state.cart[item.id] || 0) * item.price,
            0,
        );
    },

    filteredMenu(state: any): any[] {
        if (state.currentCategory === 'all') return state.menu;
        return state.menu.filter((m: any) => {
            if (m.categoryId != null) return String(m.categoryId) === String(state.currentCategory);
            return false;
        });
    },

    testimonialSlides(state: any): any[][] {
        const slides: any[][] = [];
        for (let i = 0; i < state.testimonials.length; i += 2) {
            slides.push(state.testimonials.slice(i, i + 2));
        }
        return slides.length
            ? slides
            : [[{ customer_name: 'রিভিউ নেই', testimonial_text: 'এই মুহূর্তে কোনো রিভিউ পাওয়া যায়নি।', rating: 5 }]];
    },

    slTotal(state: any): number {
        const slides: any[][] = [];
        for (let i = 0; i < state.testimonials.length; i += 2) {
            slides.push(state.testimonials.slice(i, i + 2));
        }
        return slides.length || 1;
    },

    ofGrandTotal(state: any): number {
        const total = state.menu.reduce(
            (sum: number, item: any) => sum + (state.cart[item.id] || 0) * item.price,
            0,
        );
        return Math.max(0, total + state.ofShip - state.promoDiscount);
    },
};
