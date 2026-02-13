type LanguageCode = `${string}${string}`;

/** Lingua supportata dal sistema */
export type Language = {
    readonly id: number;
    readonly name: string;
    readonly code: LanguageCode;
};

/** Media generico (immagine, video, audio, documento) */
export interface Media {
    readonly media_id: number;
    readonly media_language_id: number;
    readonly media_url: string;
    readonly media_type: 'image' | 'video' | 'audio' | 'document';
    readonly media_title: string;
    readonly media_description?: string;
}

/** Informazioni multilingua su un media */
export interface MediaInfo {
    readonly media_id: string;
    readonly media_language_id: number;
    readonly media_title_id: string;
    readonly media_description_id: string;
    readonly media_url: string;
    readonly media_type: 'image' | 'video' | 'audio' | 'document';
    readonly media_contents: Record<
        string,
        {
            media_title: string;
            media_description?: string;
            media_url: string;
        }
    >;
}

/** Sezione (dati base, riferimenti a media) */
export interface SectionRecord {
    readonly id: number;
    readonly title: string;
    readonly subtitle: string;
    readonly description: string;
    readonly video_id: number;
    readonly audio_id?: number;
    readonly image_id: number;
    readonly qrcode_id?: number;


}

/** Informazioni multilingua su una sezione */
export interface SectionData {
    readonly id: number;
    readonly title: Record<string, string>;
    readonly subtitle: Record<string, string>;
    readonly description: Record<string, string>;
    readonly video: {
        id: number | null;
        url: Record<string, string>;
        title: Record<string, string>;
    }
    readonly audio: {
        id: number | null;
        title: Record<string, string>;
        url: Record<string, string>;
        custom_properties: Record<string, string>;
    }
    readonly image: Record<string, {
        id: number;
        title: Record<string, string>;
        url: Record<string, string>;
    }>;
    readonly qrcode: Record<string, {
        id: number;
        title: Record<string, string>;
        url: Record<string, string>;
    }>;
}

export interface SectionUploadData extends SectionData {
    readonly video: {
        id: number | null;
        file?: Record<string, File>;
        title: Record<string, string>;
        url: Record<string, string>;
    };

    readonly audio: {
        id: number | null;
        file?: Record<string, File>;
        title: Record<string, string>;
        url: Record<string, string>;
    };

    readonly images: Record<string, {
        id: number | null;
        file?: Record<string, File>;
        title: Record<string, string>;
        url: Record<string, string>;
    }>;
}

export interface TermRecord {
    readonly id: number;
    readonly term: string;
    readonly definition: string;


}

/** Informazioni multilingua su una termine */
export interface TermData {
    readonly id: string;
    readonly term: Record<string, string>;
    readonly definition: Record<string, string>;
}

export interface MediaData extends Record<string, any> {
    id: number | null;
    file?: Record<string, File>;
    url?: Record<string, string> | null;
    title: Record<string, string>;
    description?: Record<string, string>;
    to_delete?: boolean;
}

type RouteFunction = (...args: any[]) => { url: string; method: string };

type ResourceRoutes = {
    show: RouteFunction;
    edit: RouteFunction;
    index: RouteFunction;
    create: RouteFunction;
    store: RouteFunction;
    update: RouteFunction;
    destroy: RouteFunction;
};
