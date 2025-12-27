import { Headphones, Mic, Music, Music2, Radio, Speaker, Volume2, Guitar, Drum, Piano, Camera, Video, Clapperboard, Film, Aperture, Focus, ScanLine, MonitorPlay, Projector, Lightbulb, Wifi, Cast } from 'lucide-react';
import { StudioType } from './StudioTypeSelector';

interface BackgroundIconsProps {
  studioType?: StudioType | null;
}

export function BackgroundIcons({ studioType }: BackgroundIconsProps) {
  // Define icon sets for different studio types
  const getIcons = () => {
    if (!studioType) {
      // Default: Mix of all studio types
      return [
        { Icon: Headphones, className: 'top-[10%] left-[5%] w-32 h-32 text-orange-500/10' },
        { Icon: Camera, className: 'top-[20%] right-[10%] w-24 h-24 text-pink-500/10' },
        { Icon: Guitar, className: 'top-[40%] left-[15%] w-40 h-40 text-orange-400/10' },
        { Icon: Video, className: 'top-[60%] right-[5%] w-36 h-36 text-pink-400/10' },
        { Icon: Mic, className: 'bottom-[15%] left-[8%] w-28 h-28 text-orange-500/10' },
        { Icon: Aperture, className: 'bottom-[25%] right-[15%] w-32 h-32 text-pink-500/10' },
        { Icon: Music2, className: 'top-[35%] right-[25%] w-20 h-20 text-orange-300/10' },
        { Icon: Film, className: 'bottom-[40%] left-[25%] w-24 h-24 text-pink-300/10' },
        { Icon: Speaker, className: 'top-[70%] left-[35%] w-28 h-28 text-orange-400/10' },
        { Icon: Camera, className: 'top-[15%] left-[40%] w-16 h-16 text-pink-400/10' },
        { Icon: Headphones, className: 'bottom-[20%] right-[35%] w-36 h-36 text-orange-500/10' },
        { Icon: Clapperboard, className: 'top-[50%] left-[5%] w-20 h-20 text-pink-500/10' },
      ];
    }

    if (studioType === 'music-production') {
      // Music Production: Musical instruments and audio equipment
      return [
        { Icon: Headphones, className: 'top-[10%] left-[5%] w-32 h-32 text-purple-500/10' },
        { Icon: Mic, className: 'top-[20%] right-[10%] w-24 h-24 text-pink-500/10' },
        { Icon: Guitar, className: 'top-[40%] left-[15%] w-40 h-40 text-purple-400/10' },
        { Icon: Speaker, className: 'top-[60%] right-[5%] w-36 h-36 text-pink-400/10' },
        { Icon: Drum, className: 'bottom-[15%] left-[8%] w-28 h-28 text-purple-500/10' },
        { Icon: Piano, className: 'bottom-[25%] right-[15%] w-32 h-32 text-pink-500/10' },
        { Icon: Music2, className: 'top-[35%] right-[25%] w-20 h-20 text-purple-300/10' },
        { Icon: Volume2, className: 'bottom-[40%] left-[25%] w-24 h-24 text-pink-300/10' },
        { Icon: Radio, className: 'top-[70%] left-[35%] w-28 h-28 text-purple-400/10' },
        { Icon: Music, className: 'top-[15%] left-[40%] w-16 h-16 text-pink-400/10' },
        { Icon: Headphones, className: 'bottom-[20%] right-[35%] w-36 h-36 text-purple-500/10' },
        { Icon: Mic, className: 'top-[50%] left-[5%] w-20 h-20 text-pink-500/10' },
        { Icon: Speaker, className: 'bottom-[35%] right-[8%] w-32 h-32 text-purple-400/10' },
        { Icon: Guitar, className: 'top-[25%] left-[30%] w-24 h-24 text-pink-300/10' },
      ];
    }

    if (studioType === 'content-creation') {
      // Content Creation: Cameras, mics, monitors, streaming equipment
      return [
        { Icon: Mic, className: 'top-[10%] left-[5%] w-32 h-32 text-blue-500/10' },
        { Icon: Camera, className: 'top-[20%] right-[10%] w-24 h-24 text-cyan-500/10' },
        { Icon: MonitorPlay, className: 'top-[40%] left-[15%] w-40 h-40 text-blue-400/10' },
        { Icon: Headphones, className: 'top-[60%] right-[5%] w-36 h-36 text-cyan-400/10' },
        { Icon: Wifi, className: 'bottom-[15%] left-[8%] w-28 h-28 text-blue-500/10' },
        { Icon: Video, className: 'bottom-[25%] right-[15%] w-32 h-32 text-cyan-500/10' },
        { Icon: Cast, className: 'top-[35%] right-[25%] w-20 h-20 text-blue-300/10' },
        { Icon: Lightbulb, className: 'bottom-[40%] left-[25%] w-24 h-24 text-cyan-300/10' },
        { Icon: Speaker, className: 'top-[70%] left-[35%] w-28 h-28 text-blue-400/10' },
        { Icon: Camera, className: 'top-[15%] left-[40%] w-16 h-16 text-cyan-400/10' },
        { Icon: Mic, className: 'bottom-[20%] right-[35%] w-36 h-36 text-blue-500/10' },
        { Icon: MonitorPlay, className: 'top-[50%] left-[5%] w-20 h-20 text-cyan-500/10' },
        { Icon: Video, className: 'bottom-[35%] right-[8%] w-32 h-32 text-blue-400/10' },
        { Icon: Headphones, className: 'top-[25%] left-[30%] w-24 h-24 text-cyan-300/10' },
      ];
    }

    if (studioType === 'photography') {
      // Photography: Cameras, lenses, lighting, aperture
      return [
        { Icon: Camera, className: 'top-[10%] left-[5%] w-32 h-32 text-green-500/10' },
        { Icon: Aperture, className: 'top-[20%] right-[10%] w-24 h-24 text-emerald-500/10' },
        { Icon: Focus, className: 'top-[40%] left-[15%] w-40 h-40 text-green-400/10' },
        { Icon: Lightbulb, className: 'top-[60%] right-[5%] w-36 h-36 text-emerald-400/10' },
        { Icon: ScanLine, className: 'bottom-[15%] left-[8%] w-28 h-28 text-green-500/10' },
        { Icon: Camera, className: 'bottom-[25%] right-[15%] w-32 h-32 text-emerald-500/10' },
        { Icon: Aperture, className: 'top-[35%] right-[25%] w-20 h-20 text-green-300/10' },
        { Icon: Focus, className: 'bottom-[40%] left-[25%] w-24 h-24 text-emerald-300/10' },
        { Icon: Lightbulb, className: 'top-[70%] left-[35%] w-28 h-28 text-green-400/10' },
        { Icon: Camera, className: 'top-[15%] left-[40%] w-16 h-16 text-emerald-400/10' },
        { Icon: Aperture, className: 'bottom-[20%] right-[35%] w-36 h-36 text-green-500/10' },
        { Icon: ScanLine, className: 'top-[50%] left-[5%] w-20 h-20 text-emerald-500/10' },
        { Icon: Focus, className: 'bottom-[35%] right-[8%] w-32 h-32 text-green-400/10' },
        { Icon: Camera, className: 'top-[25%] left-[30%] w-24 h-24 text-emerald-300/10' },
      ];
    }

    if (studioType === 'videography') {
      // Videography: Video cameras, clapperboards, film, lights, projectors
      return [
        { Icon: Video, className: 'top-[10%] left-[5%] w-32 h-32 text-orange-500/10' },
        { Icon: Camera, className: 'top-[20%] right-[10%] w-24 h-24 text-red-500/10' },
        { Icon: Clapperboard, className: 'top-[40%] left-[15%] w-40 h-40 text-orange-400/10' },
        { Icon: Film, className: 'top-[60%] right-[5%] w-36 h-36 text-red-400/10' },
        { Icon: Lightbulb, className: 'bottom-[15%] left-[8%] w-28 h-28 text-orange-500/10' },
        { Icon: Projector, className: 'bottom-[25%] right-[15%] w-32 h-32 text-red-500/10' },
        { Icon: Video, className: 'top-[35%] right-[25%] w-20 h-20 text-orange-300/10' },
        { Icon: Mic, className: 'bottom-[40%] left-[25%] w-24 h-24 text-red-300/10' },
        { Icon: Camera, className: 'top-[70%] left-[35%] w-28 h-28 text-orange-400/10' },
        { Icon: Clapperboard, className: 'top-[15%] left-[40%] w-16 h-16 text-red-400/10' },
        { Icon: Film, className: 'bottom-[20%] right-[35%] w-36 h-36 text-orange-500/10' },
        { Icon: Video, className: 'top-[50%] left-[5%] w-20 h-20 text-red-500/10' },
        { Icon: Lightbulb, className: 'bottom-[35%] right-[8%] w-32 h-32 text-orange-400/10' },
        { Icon: Camera, className: 'top-[25%] left-[30%] w-24 h-24 text-red-300/10' },
      ];
    }

    return [];
  };

  const icons = getIcons();

  return (
    <div className="fixed inset-0 overflow-hidden pointer-events-none z-0">
      {/* Gradient orbs for extra ambiance */}
      <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-pulse" />
      <div className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-pink-500/10 rounded-full blur-3xl animate-pulse" style={{ animationDelay: '1s' }} />
      
      {/* Floating icons */}
      {icons.map((item, index) => (
        <div
          key={index}
          className={`absolute ${item.className} animate-float`}
          style={{
            animationDelay: `${index * 0.5}s`,
            animationDuration: `${15 + (index % 5) * 2}s`,
          }}
        >
          <item.Icon className="w-full h-full" strokeWidth={0.5} />
        </div>
      ))}
    </div>
  );
}