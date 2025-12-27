import { useState, useEffect } from 'react';
import { StudioTypeSelector, StudioType } from './components/StudioTypeSelector';
import { StudioBuilder } from './components/StudioBuilder';
import { BackgroundIcons } from './components/BackgroundIcons';
import { LanguageProvider } from './contexts/LanguageContext';
import { Home, Info, Mail, LayoutDashboard, Shield, Menu, X } from 'lucide-react';
import { fetchSettings } from '../utils/api';

type View = 'home' | 'about' | 'contact';

interface SystemSettings {
  systemName: string;
  logoUrl: string;
  adminUrl: string;
}

export default function App() {
  const [currentView, setCurrentView] = useState<View>('home');
  const [selectedStudioType, setSelectedStudioType] = useState<StudioType | null>(null);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [settings, setSettings] = useState<SystemSettings>({
    systemName: 'Studio Builder',
    logoUrl: '',
    adminUrl: '/Buildyourstudio/backend/admin/'
  });

  useEffect(() => {
    fetchSettings().then(data => {
      if (data) setSettings(data);
    }).catch(err => console.error("Failed to load settings", err));
  }, []);

  const handleStudioTypeSelect = (type: StudioType) => {
    setSelectedStudioType(type);
  };

  // Get theme colors based on studio type
  const getThemeColors = () => {
    if (!selectedStudioType) {
      return {
        bgGradient: 'from-slate-950 via-orange-950 to-pink-950',
        navBg: 'bg-slate-900/80',
        accentGradient: 'from-orange-500 to-pink-500',
        accentColor: 'text-orange-400',
      };
    }

    switch (selectedStudioType) {
      case 'music-production':
        return {
          bgGradient: 'from-slate-950 via-purple-950 to-pink-950',
          navBg: 'bg-slate-900/80',
          accentGradient: 'from-purple-500 to-pink-500',
          accentColor: 'text-purple-400',
        };
      case 'content-creation':
        return {
          bgGradient: 'from-slate-950 via-blue-950 to-cyan-950',
          navBg: 'bg-slate-900/80',
          accentGradient: 'from-blue-500 to-cyan-500',
          accentColor: 'text-blue-400',
        };
      case 'photography':
        return {
          bgGradient: 'from-slate-950 via-green-950 to-emerald-950',
          navBg: 'bg-slate-900/80',
          accentGradient: 'from-green-500 to-emerald-500',
          accentColor: 'text-green-400',
        };
      case 'videography':
        return {
          bgGradient: 'from-slate-950 via-orange-950 to-red-950',
          navBg: 'bg-slate-900/80',
          accentGradient: 'from-orange-500 to-red-500',
          accentColor: 'text-orange-400',
        };
      default:
        return {
          bgGradient: 'from-slate-950 via-orange-950 to-pink-950',
          navBg: 'bg-slate-900/80',
          accentGradient: 'from-orange-500 to-pink-500',
          accentColor: 'text-orange-400',
        };
    }
  };

  const theme = getThemeColors();

  return (
    <LanguageProvider>
      <div className={`min-h-screen bg-gradient-to-br ${theme.bgGradient} relative`}>
        {/* Background Icons */}
        <BackgroundIcons studioType={selectedStudioType} />

        {/* Navigation Header */}
        <nav className={`fixed top-0 left-0 right-0 z-50 ${theme.navBg} backdrop-blur-xl border-b border-slate-700/50`}>
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex items-center justify-between h-16">
              {/* Logo */}
              <button
                onClick={() => setCurrentView('home')}
                className="flex items-center gap-2 text-white hover:text-cyan-400 transition-colors"
              >
                {settings.logoUrl ? (
                  <img src={settings.logoUrl} alt="Logo" className="h-8 w-auto object-contain rounded-lg" />
                ) : (
                  <div className="p-2 bg-gradient-to-br from-cyan-500 to-purple-500 rounded-lg">
                    <Home className="w-5 h-5" />
                  </div>
                )}
                <span className="font-bold text-lg">{settings.systemName}</span>
              </button>

              {/* Desktop Navigation Items */}
              <div className="hidden md:flex items-center gap-6">
                <button
                  onClick={() => setCurrentView('home')}
                  className={`flex items-center gap-2 px-4 py-2 rounded-lg transition-all ${currentView === 'home'
                    ? 'bg-slate-800/50 text-cyan-400'
                    : 'text-slate-300 hover:text-white hover:bg-slate-800/30'
                    }`}
                >
                  <Home className="w-4 h-4" />
                  <span>Home</span>
                </button>

                <button
                  onClick={() => setCurrentView('about')}
                  className={`flex items-center gap-2 px-4 py-2 rounded-lg transition-all ${currentView === 'about'
                    ? 'bg-slate-800/50 text-cyan-400'
                    : 'text-slate-300 hover:text-white hover:bg-slate-800/30'
                    }`}
                >
                  <Info className="w-4 h-4" />
                  <span>About</span>
                </button>

                <button
                  onClick={() => setCurrentView('contact')}
                  className={`flex items-center gap-2 px-4 py-2 rounded-lg transition-all ${currentView === 'contact'
                    ? 'bg-slate-800/50 text-cyan-400'
                    : 'text-slate-300 hover:text-white hover:bg-slate-800/30'
                    }`}
                >
                  <Mail className="w-4 h-4" />
                  <span>Contact</span>
                </button>

                {/* Admin Dashboard Link - Dynamic */}
                <a
                  href={settings.adminUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500/20 to-cyan-500/20 border border-purple-500/50 rounded-lg text-purple-300 hover:from-purple-500/30 hover:to-cyan-500/30 transition-all"
                >
                  <LayoutDashboard className="w-4 h-4" />
                  <span>Admin Panel</span>
                </a>
              </div>

              {/* Mobile Menu Button */}
              <button
                onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                className="md:hidden p-2 text-slate-300 hover:text-white hover:bg-slate-800/30 rounded-lg transition-colors"
                aria-label="Toggle menu"
              >
                {isMobileMenuOpen ? (
                  <X className="w-6 h-6" />
                ) : (
                  <Menu className="w-6 h-6" />
                )}
              </button>
            </div>
          </div>

          {/* Mobile Menu Drawer */}
          {isMobileMenuOpen && (
            <div className={`md:hidden absolute top-16 left-0 right-0 ${theme.navBg} backdrop-blur-xl border-b border-slate-700/50 shadow-xl animate-in slide-in-from-top-2 duration-200`}>
              <div className="px-4 py-4 space-y-2">
                <button
                  onClick={() => { setCurrentView('home'); setIsMobileMenuOpen(false); }}
                  className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all ${currentView === 'home'
                    ? 'bg-slate-800/50 text-cyan-400'
                    : 'text-slate-300 hover:text-white hover:bg-slate-800/30'
                    }`}
                >
                  <Home className="w-5 h-5" />
                  <span className="font-medium">Home</span>
                </button>

                <button
                  onClick={() => { setCurrentView('about'); setIsMobileMenuOpen(false); }}
                  className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all ${currentView === 'about'
                    ? 'bg-slate-800/50 text-cyan-400'
                    : 'text-slate-300 hover:text-white hover:bg-slate-800/30'
                    }`}
                >
                  <Info className="w-5 h-5" />
                  <span className="font-medium">About</span>
                </button>

                <button
                  onClick={() => { setCurrentView('contact'); setIsMobileMenuOpen(false); }}
                  className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all ${currentView === 'contact'
                    ? 'bg-slate-800/50 text-cyan-400'
                    : 'text-slate-300 hover:text-white hover:bg-slate-800/30'
                    }`}
                >
                  <Mail className="w-5 h-5" />
                  <span className="font-medium">Contact</span>
                </button>

                <div className="pt-2 border-t border-slate-700/50 mt-2">
                  <a
                    href={settings.adminUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="w-full flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-purple-500/20 to-cyan-500/20 border border-purple-500/50 rounded-lg text-purple-300 hover:from-purple-500/30 hover:to-cyan-500/30 transition-all shadow-lg shadow-purple-900/20"
                  >
                    <LayoutDashboard className="w-5 h-5" />
                    <span className="font-medium">Admin Panel</span>
                  </a>
                </div>
              </div>
            </div>
          )}
        </nav>

        {/* Main Content */}
        <div className="pt-16 relative z-10">
          {currentView === 'home' && !selectedStudioType && (
            <StudioTypeSelector onSelectType={handleStudioTypeSelect} />
          )}

          {currentView === 'home' && selectedStudioType && (
            <StudioBuilder
              studioType={selectedStudioType}
              onBack={() => setSelectedStudioType(null)}
            />
          )}

          {currentView === 'about' && (
            <div className="min-h-screen flex items-center justify-center px-4">
              <div className="max-w-3xl mx-auto text-center">
                <h1 className="text-5xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent mb-6">
                  About Studio Builder
                </h1>
                <p className="text-xl text-slate-300 mb-8">
                  Studio Builder is your comprehensive solution for building a professional music production setup.
                  We help you select the perfect combination of software, plugins, and storage to match your creative needs and budget.
                </p>
                <div className="grid md:grid-cols-3 gap-6 mt-12">
                  <div className="bg-slate-900/50 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6">
                    <div className="text-4xl mb-4">🎵</div>
                    <h3 className="text-white font-semibold mb-2">Curated Selection</h3>
                    <p className="text-slate-400 text-sm">Hand-picked tools for music producers at every level</p>
                  </div>
                  <div className="bg-slate-900/50 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6">
                    <div className="text-4xl mb-4">💾</div>
                    <h3 className="text-white font-semibold mb-2">Storage Calculator</h3>
                    <p className="text-slate-400 text-sm">Automatically calculates your storage needs</p>
                  </div>
                  <div className="bg-slate-900/50 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6">
                    <div className="text-4xl mb-4">🚚</div>
                    <h3 className="text-white font-semibold mb-2">Fast Delivery</h3>
                    <p className="text-slate-400 text-sm">Ships to Tanzania and neighboring countries</p>
                  </div>
                </div>
              </div>
            </div>
          )}

          {currentView === 'contact' && (
            <div className="min-h-screen flex items-center justify-center px-4">
              <div className="max-w-2xl mx-auto">
                <h1 className="text-5xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent mb-6 text-center">
                  Get In Touch
                </h1>
                <div className="bg-slate-900/50 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-8">
                  <div className="space-y-6">
                    <div>
                      <label className="block text-sm font-medium text-slate-300 mb-2">Name</label>
                      <input
                        type="text"
                        placeholder="Your name"
                        className="w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-500/50 focus:ring-2 focus:ring-purple-500/20 transition-all"
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-slate-300 mb-2">Email</label>
                      <input
                        type="email"
                        placeholder="your.email@example.com"
                        className="w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-500/50 focus:ring-2 focus:ring-purple-500/20 transition-all"
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-slate-300 mb-2">Message</label>
                      <textarea
                        rows={5}
                        placeholder="How can we help you?"
                        className="w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-purple-500/50 focus:ring-2 focus:ring-purple-500/20 transition-all resize-none"
                      />
                    </div>
                    <button className="w-full py-3 px-6 bg-gradient-to-r from-purple-500 to-cyan-500 text-white rounded-xl hover:from-purple-400 hover:to-cyan-400 transition-all shadow-lg shadow-purple-500/50 font-medium">
                      Send Message
                    </button>
                  </div>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </LanguageProvider>
  );
}