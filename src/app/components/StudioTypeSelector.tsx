import { Camera, Video, Mic, Music2 } from 'lucide-react';

export type StudioType = 'music-production' | 'content-creation' | 'photography' | 'videography';

interface StudioTypeSelectorProps {
  onSelectType: (type: StudioType) => void;
}

export function StudioTypeSelector({ onSelectType }: StudioTypeSelectorProps) {
  const studioTypes = [
    {
      id: 'music-production' as StudioType,
      title: 'Music Production Studio',
      description: 'Complete setup for recording, mixing, and producing music',
      icon: <Music2 className="w-12 h-12" />,
      gradient: 'from-purple-500 to-pink-500',
      bgGradient: 'from-purple-500/20 to-pink-500/20',
    },
    {
      id: 'content-creation' as StudioType,
      title: 'Content Creation Studio',
      description: 'Everything for streaming, podcasting, and content creation',
      icon: <Mic className="w-12 h-12" />,
      gradient: 'from-blue-500 to-cyan-500',
      bgGradient: 'from-blue-500/20 to-cyan-500/20',
    },
    {
      id: 'photography' as StudioType,
      title: 'Photography Studio',
      description: 'Professional photography equipment and lighting setup',
      icon: <Camera className="w-12 h-12" />,
      gradient: 'from-green-500 to-emerald-500',
      bgGradient: 'from-green-500/20 to-emerald-500/20',
    },
    {
      id: 'videography' as StudioType,
      title: 'Videography Studio',
      description: 'Complete video production and filming equipment',
      icon: <Video className="w-12 h-12" />,
      gradient: 'from-orange-500 to-red-500',
      bgGradient: 'from-orange-500/20 to-red-500/20',
    },
  ];

  return (
    <div className="min-h-screen flex items-center justify-center px-4 py-12">
      <div className="max-w-6xl w-full">
        <div className="text-center mb-12">
          <h1 className="text-5xl md:text-6xl font-bold bg-gradient-to-r from-orange-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-4">
            Build Your Perfect Studio
          </h1>
          <p className="text-xl text-slate-300">
            Choose your studio type to get started with personalized equipment recommendations
          </p>
        </div>

        <div className="grid md:grid-cols-2 gap-6">
          {studioTypes.map((studio) => (
            <button
              key={studio.id}
              onClick={() => onSelectType(studio.id)}
              className={`group relative overflow-hidden bg-slate-900/50 backdrop-blur-xl border-2 border-slate-700/50 rounded-3xl p-8 text-left transition-all hover:border-transparent hover:scale-105`}
            >
              {/* Gradient overlay on hover */}
              <div className={`absolute inset-0 bg-gradient-to-br ${studio.bgGradient} opacity-0 group-hover:opacity-100 transition-opacity duration-300`} />
              
              {/* Content */}
              <div className="relative z-10">
                <div className={`inline-flex p-4 bg-gradient-to-br ${studio.gradient} rounded-2xl mb-4 text-white`}>
                  {studio.icon}
                </div>
                
                <h3 className="text-2xl font-bold text-white mb-2">
                  {studio.title}
                </h3>
                
                <p className="text-slate-400 group-hover:text-slate-300 transition-colors">
                  {studio.description}
                </p>

                <div className="mt-6 flex items-center gap-2 text-sm">
                  <span className={`px-3 py-1 bg-gradient-to-r ${studio.gradient} text-white rounded-full font-medium`}>
                    Get Started
                  </span>
                  <span className="text-slate-500 group-hover:text-slate-400 transition-colors">
                    →
                  </span>
                </div>
              </div>
            </button>
          ))}
        </div>

        <div className="mt-12 text-center">
          <p className="text-sm text-slate-500">
            Not sure which one to choose? Each studio type comes with curated equipment recommendations
          </p>
        </div>
      </div>
    </div>
  );
}
