import { useState, useEffect } from 'react';
import { StudioType } from './StudioTypeSelector';
import { Laptop, Mic, Radio, Headphones, Speaker, Piano, Package, Music2, Guitar, ChevronDown, ChevronRight, Plus, Minus, ShoppingCart, X, Loader2 } from 'lucide-react';
import { useLanguage } from '../contexts/LanguageContext';
import { fetchProducts, fetchCategories } from '../../utils/api';

interface StudioBuilderProps {
  studioType: StudioType;
  onBack: () => void;
}

export interface LibraryPack {
  id: string;
  name: string;
  description: string;
  price: number;
  fileSize: number;
}

export interface Product {
  id: string;
  name: string;
  description: string;
  priceUSD: number;
  priceTZS: number;
  tier: 'cheap' | 'medium' | 'expensive';
  category: string;
  imageUrl?: string;
  fileSize: number;
  libraryPacks?: LibraryPack[];
}

export interface Category {
  id: string;
  name: string;
  icon: string;
  description: string;
  hasPreQuestion?: boolean;
  preQuestion?: {
    question: string;
    options: Array<{ label: string; value: string }>;
  };
  required: boolean;
}

interface CartItem {
  product: Product;
  quantity: number;
}

const iconMap: { [key: string]: any } = {
  Laptop,
  Mic,
  Radio,
  Headphones,
  Speaker,
  Piano,
  Package,
  Music2,
  Guitar,
};

export function StudioBuilder({ studioType, onBack }: StudioBuilderProps) {
  const { currency } = useLanguage();
  const [categories, setCategories] = useState<Category[]>([]);
  const [products, setProducts] = useState<Product[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState('');

  const [expandedCategory, setExpandedCategory] = useState<string | null>(null);
  const [computerChoice, setComputerChoice] = useState<'yes' | 'no' | null>(null);
  const [computerType, setComputerType] = useState<'laptop' | 'desktop' | null>(null);
  const [selectedTier, setSelectedTier] = useState<{ [categoryId: string]: 'cheap' | 'medium' | 'expensive' | null }>({});
  const [cart, setCart] = useState<CartItem[]>([]);
  const [showCart, setShowCart] = useState(false);
  const [expandedImage, setExpandedImage] = useState<{ url: string; name: string } | null>(null);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      setIsLoading(true);
      const [cats, prods] = await Promise.all([
        fetchCategories(),
        fetchProducts()
      ]);

      // Map icon strings to match what we expect (if needed) or ensure backend sends correct icon names
      // Also manually attach the 'hasPreQuestion' logic to the 'computer' category if missing from DB
      const enhancedCats = cats.map((c: any) => {
        if (c.id === 'computer') {
          return {
            ...c,
            hasPreQuestion: true,
            preQuestion: {
              question: 'Do you have a computer?',
              options: [
                { label: 'Yes, I have a computer', value: 'yes' },
                { label: 'No, I need a computer', value: 'no' },
              ],
            }
          };
        }
        return c;
      });

      setCategories(enhancedCats);
      setProducts(prods);
    } catch (err) {
      console.error('Failed to load studio data:', err);
      setError('Failed to load products. Please check your connection.');
    } finally {
      setIsLoading(false);
    }
  };

  // Get theme colors based on studio type
  const getTheme = () => {
    switch (studioType) {
      case 'music-production':
        return {
          gradient: 'from-purple-500 via-pink-500 to-purple-600',
          bg: 'bg-purple-500/10',
          border: 'border-purple-500/30',
          text: 'text-purple-400',
          button: 'bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600',
        };
      default:
        return {
          gradient: 'from-orange-500 via-pink-500 to-purple-500',
          bg: 'bg-orange-500/10',
          border: 'border-orange-500/30',
          text: 'text-orange-400',
          button: 'bg-gradient-to-r from-orange-500 to-pink-500 hover:from-orange-600 hover:to-pink-600',
        };
    }
  };

  const theme = getTheme();

  const toggleCategory = (categoryId: string) => {
    setExpandedCategory(expandedCategory === categoryId ? null : categoryId);
  };

  const handleComputerChoice = (choice: 'yes' | 'no') => {
    setComputerChoice(choice);
    if (choice === 'yes') {
      setComputerType(null);
      // Automatically close the computer category and move to next
      setExpandedCategory(null);
    }
  };

  const handleComputerType = (type: 'laptop' | 'desktop') => {
    setComputerType(type);
  };

  const handleTierSelect = (categoryId: string, tier: 'cheap' | 'medium' | 'expensive') => {
    setSelectedTier({ ...selectedTier, [categoryId]: tier });
  };

  const addToCart = (product: Product) => {
    const existingItem = cart.find(item => item.product.id === product.id);
    if (existingItem) {
      setCart(cart.map(item =>
        item.product.id === product.id
          ? { ...item, quantity: item.quantity + 1 }
          : item
      ));
    } else {
      setCart([...cart, { product, quantity: 1 }]);
    }
  };

  const removeFromCart = (productId: string) => {
    setCart(cart.filter(item => item.product.id !== productId));
  };

  const updateQuantity = (productId: string, change: number) => {
    setCart(cart.map(item => {
      if (item.product.id === productId) {
        const newQuantity = Math.max(0, item.quantity + change);
        return { ...item, quantity: newQuantity };
      }
      return item;
    }).filter(item => item.quantity > 0));
  };

  const getTotalPrice = () => {
    const total = cart.reduce((sum, item) => {
      const price = currency === 'USD' ? item.product.priceUSD : item.product.priceTZS;
      return sum + (price * item.quantity);
    }, 0);
    return total;
  };

  const formatPrice = (priceUSD: number, priceTZS: number) => {
    if (currency === 'USD') {
      return `$${priceUSD.toLocaleString()}`;
    }
    return `${priceTZS.toLocaleString()} TZS`;
  };

  const getTierLabel = (tier: 'cheap' | 'medium' | 'expensive') => {
    switch (tier) {
      case 'cheap':
        return 'Budget Friendly';
      case 'medium':
        return 'Mid Range';
      case 'expensive':
        return 'Premium';
    }
  };

  const getTierColor = (tier: 'cheap' | 'medium' | 'expensive') => {
    switch (tier) {
      case 'cheap':
        return 'from-green-500 to-emerald-500';
      case 'medium':
        return 'from-blue-500 to-cyan-500';
      case 'expensive':
        return 'from-orange-500 to-red-500';
    }
  };

  const getProductsForCategory = (categoryId: string) => {
    if (categoryId === 'computer') {
      if (computerType === 'laptop') {
        return products.filter(p => p.category === 'computer-laptop');
      } else if (computerType === 'desktop') {
        return products.filter(p => p.category === 'computer-desktop');
      }
      return [];
    }
    return products.filter(p => p.category === categoryId);
  };

  const renderCategoryContent = (category: Category) => {
    // Handle computer category with pre-question
    if (category.id === 'computer' && category.hasPreQuestion) {
      return (
        <div className="space-y-6">
          {/* Computer Question */}
          <div className="space-y-4">
            <p className="text-slate-300">{category.preQuestion?.question}</p>
            <div className="grid grid-cols-2 gap-4">
              {category.preQuestion?.options.map((option) => (
                <button
                  key={option.value}
                  onClick={() => handleComputerChoice(option.value as 'yes' | 'no')}
                  className={`p-4 rounded-xl border-2 transition-all ${computerChoice === option.value
                    ? `${theme.border} ${theme.bg}`
                    : 'border-slate-700 bg-slate-800/50 hover:border-slate-600'
                    }`}
                >
                  <span className="text-slate-200">{option.label}</span>
                </button>
              ))}
            </div>
          </div>

          {/* Computer Type Selection */}
          {computerChoice === 'no' && (
            <div className="space-y-4 animate-in fade-in duration-300">
              <p className="text-slate-300">Choose your computer type:</p>
              <div className="grid grid-cols-2 gap-4">
                <button
                  onClick={() => handleComputerType('laptop')}
                  className={`p-4 rounded-xl border-2 transition-all ${computerType === 'laptop'
                    ? `${theme.border} ${theme.bg}`
                    : 'border-slate-700 bg-slate-800/50 hover:border-slate-600'
                    }`}
                >
                  <Laptop className="w-8 h-8 mx-auto mb-2 text-slate-400" />
                  <span className="text-slate-200">Laptop</span>
                </button>
                <button
                  onClick={() => handleComputerType('desktop')}
                  className={`p-4 rounded-xl border-2 transition-all ${computerType === 'desktop'
                    ? `${theme.border} ${theme.bg}`
                    : 'border-slate-700 bg-slate-800/50 hover:border-slate-600'
                    }`}
                >
                  <Package className="w-8 h-8 mx-auto mb-2 text-slate-400" />
                  <span className="text-slate-200">Desktop</span>
                </button>
              </div>
            </div>
          )}

          {/* Show Products */}
          {computerChoice === 'no' && computerType && renderProductSelection(category.id)}
        </div>
      );
    }

    // Regular categories
    return renderProductSelection(category.id);
  };

  const renderProductSelection = (categoryId: string) => {
    const catProducts = getProductsForCategory(categoryId);
    const tiers: Array<'cheap' | 'medium' | 'expensive'> = ['cheap', 'medium', 'expensive'];

    // Check if there are any products for this category to avoid empty tier section
    if (catProducts.length === 0) {
      return (
        <div className="text-slate-400 text-center py-4">No products available in this category yet.</div>
      );
    }

    return (
      <div className="space-y-6">
        {/* Tier Selection */}
        <div className="space-y-4">
          <p className="text-slate-300">Select your budget range:</p>
          <div className="grid grid-cols-3 gap-4">
            {tiers.map((tier) => {
              const tierProducts = catProducts.filter(p => p.tier === tier);
              if (tierProducts.length === 0) return null;

              const minPrice = Math.min(...tierProducts.map(p => currency === 'USD' ? p.priceUSD : p.priceTZS));
              const maxPrice = Math.max(...tierProducts.map(p => currency === 'USD' ? p.priceUSD : p.priceTZS));

              return (
                <button
                  key={tier}
                  onClick={() => handleTierSelect(categoryId, tier)}
                  className={`p-4 rounded-xl border-2 transition-all ${selectedTier[categoryId] === tier
                    ? `${theme.border} ${theme.bg}`
                    : 'border-slate-700 bg-slate-800/50 hover:border-slate-600'
                    }`}
                >
                  <div className={`text-sm font-semibold mb-2 bg-gradient-to-r ${getTierColor(tier)} bg-clip-text text-transparent`}>
                    {getTierLabel(tier)}
                  </div>
                  <div className="text-xs text-slate-400">
                    {formatPrice(minPrice, minPrice)} - {formatPrice(maxPrice, maxPrice)}
                  </div>
                </button>
              );
            })}
          </div>
        </div>

        {/* Product List */}
        {selectedTier[categoryId] && (
          <div className="space-y-4 animate-in fade-in duration-300">
            <p className="text-slate-300">Choose your products:</p>
            <div className="grid gap-4">
              {catProducts
                .filter(p => p.tier === selectedTier[categoryId])
                .map((product) => {
                  const cartItem = cart.find(item => item.product.id === product.id);
                  return (
                    <div
                      key={product.id}
                      className="p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:border-slate-600 transition-all"
                    >
                      <div className="flex gap-4">
                        {/* Product Image */}
                        {product.imageUrl && (
                          <div className="flex-shrink-0">
                            <img
                              src={product.imageUrl}
                              alt={product.name}
                              className="w-24 h-24 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                              onClick={() => setExpandedImage({ url: product.imageUrl!, name: product.name })}
                            />
                          </div>
                        )}

                        {/* Product Details */}
                        <div className="flex-1">
                          <div className="flex justify-between items-start mb-2">
                            <div className="flex-1">
                              <h4 className="font-semibold text-slate-200">{product.name}</h4>
                              <p className="text-sm text-slate-400 mt-1">{product.description}</p>
                            </div>
                            <div className={`text-lg font-bold ${theme.text} ml-4`}>
                              {formatPrice(product.priceUSD, product.priceTZS)}
                            </div>
                          </div>

                          <div className="flex justify-between items-center mt-3">
                            {cartItem ? (
                              <div className="flex items-center gap-2">
                                <button
                                  onClick={() => updateQuantity(product.id, -1)}
                                  className="p-1 rounded-lg bg-slate-700 hover:bg-slate-600 transition-colors"
                                >
                                  <Minus className="w-4 h-4 text-slate-300" />
                                </button>
                                <span className="text-slate-200 font-semibold min-w-[2rem] text-center">
                                  {cartItem.quantity}
                                </span>
                                <button
                                  onClick={() => updateQuantity(product.id, 1)}
                                  className="p-1 rounded-lg bg-slate-700 hover:bg-slate-600 transition-colors"
                                >
                                  <Plus className="w-4 h-4 text-slate-300" />
                                </button>
                                <button
                                  onClick={() => removeFromCart(product.id)}
                                  className="ml-2 p-1 rounded-lg bg-red-500/20 hover:bg-red-500/30 transition-colors"
                                >
                                  <X className="w-4 h-4 text-red-400" />
                                </button>
                              </div>
                            ) : (
                              <button
                                onClick={() => addToCart(product)}
                                className={`px-4 py-2 rounded-lg ${theme.button} text-white transition-all flex items-center gap-2`}
                              >
                                <Plus className="w-4 h-4" />
                                Add to Cart
                              </button>
                            )}
                          </div>
                        </div>
                      </div>
                    </div>
                  );
                })}
            </div>
          </div>
        )}
      </div>
    );
  };

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <Loader2 className="w-12 h-12 text-purple-500 animate-spin mx-auto mb-4" />
          <p className="text-slate-400">Loading studio data...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center max-w-md px-4">
          <div className="text-red-400 text-5xl mb-4">⚠️</div>
          <h3 className="text-xl font-bold text-white mb-2">Something went wrong</h3>
          <p className="text-slate-400 mb-6">{error}</p>
          <button
            onClick={loadData}
            className="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors"
          >
            Try Again
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen px-4 py-12">
      <div className="max-w-6xl mx-auto">
        {/* Header */}
        <div className="mb-8">
          <button
            onClick={onBack}
            className="text-slate-400 hover:text-slate-300 transition-colors mb-4"
          >
            ← Back to Studio Selection
          </button>
          <h1 className={`text-4xl font-bold bg-gradient-to-r ${theme.gradient} bg-clip-text text-transparent mb-2`}>
            Build Your Music Production Studio
          </h1>
          <p className="text-slate-400">Select equipment from the categories below. You can skip categories or select multiple items from each.</p>
        </div>

        {/* Shopping Cart Button */}
        <div className="fixed bottom-8 right-8 z-50">
          <button
            onClick={() => setShowCart(!showCart)}
            className={`${theme.button} text-white px-6 py-4 rounded-full shadow-2xl flex items-center gap-3 transition-all hover:scale-105`}
          >
            <ShoppingCart className="w-6 h-6" />
            <span className="font-semibold">{cart.length} Items</span>
            <span className="ml-2 px-3 py-1 bg-white/20 rounded-full">
              {formatPrice(getTotalPrice(), getTotalPrice())}
            </span>
          </button>
        </div>

        {/* Cart Sidebar */}
        {showCart && (
          <div className="fixed inset-y-0 right-0 w-96 bg-slate-900 border-l border-slate-700 shadow-2xl z-50 overflow-y-auto">
            <div className="p-6">
              <div className="flex justify-between items-center mb-6">
                <h2 className="text-2xl font-bold text-slate-200">Your Cart</h2>
                <button
                  onClick={() => setShowCart(false)}
                  className="p-2 rounded-lg hover:bg-slate-800 transition-colors"
                >
                  <X className="w-6 h-6 text-slate-400" />
                </button>
              </div>

              {cart.length === 0 ? (
                <div className="text-center py-12">
                  <ShoppingCart className="w-16 h-16 text-slate-600 mx-auto mb-4" />
                  <p className="text-slate-400">Your cart is empty</p>
                </div>
              ) : (
                <div className="space-y-4">
                  {cart.map((item) => (
                    <div key={item.product.id} className="p-4 rounded-xl bg-slate-800 border border-slate-700">
                      <div className="flex justify-between items-start mb-2">
                        <h4 className="font-semibold text-slate-200 flex-1">{item.product.name}</h4>
                        <button
                          onClick={() => removeFromCart(item.product.id)}
                          className="p-1 rounded hover:bg-slate-700 transition-colors"
                        >
                          <X className="w-4 h-4 text-slate-400" />
                        </button>
                      </div>
                      <div className="flex justify-between items-center mt-3">
                        <div className="flex items-center gap-2">
                          <button
                            onClick={() => updateQuantity(item.product.id, -1)}
                            className="p-1 rounded bg-slate-700 hover:bg-slate-600"
                          >
                            <Minus className="w-4 h-4 text-slate-300" />
                          </button>
                          <span className="text-slate-200 min-w-[2rem] text-center">{item.quantity}</span>
                          <button
                            onClick={() => updateQuantity(item.product.id, 1)}
                            className="p-1 rounded bg-slate-700 hover:bg-slate-600"
                          >
                            <Plus className="w-4 h-4 text-slate-300" />
                          </button>
                        </div>
                        <div className={`font-bold ${theme.text}`}>
                          {formatPrice(
                            item.product.priceUSD * item.quantity,
                            item.product.priceTZS * item.quantity
                          )}
                        </div>
                      </div>
                    </div>
                  ))}

                  {/* Total */}
                  <div className="pt-4 border-t border-slate-700">
                    <div className="flex justify-between items-center mb-4">
                      <span className="text-xl font-bold text-slate-200">Total</span>
                      <span className={`text-2xl font-bold ${theme.text}`}>
                        {formatPrice(getTotalPrice(), getTotalPrice())}
                      </span>
                    </div>
                    <button className={`w-full py-3 rounded-xl ${theme.button} text-white font-semibold transition-all hover:scale-105`}>
                      Checkout
                    </button>
                  </div>
                </div>
              )}
            </div>
          </div>
        )}

        {/* Categories */}
        <div className="space-y-4">
          {categories.map((category) => {
            const Icon = iconMap[category.icon];
            const isExpanded = expandedCategory === category.id;

            return (
              <div
                key={category.id}
                className="rounded-2xl bg-slate-800/50 border border-slate-700 overflow-hidden transition-all hover:border-slate-600"
              >
                {/* Category Header */}
                <button
                  onClick={() => toggleCategory(category.id)}
                  className="w-full p-6 flex items-center justify-between transition-colors hover:bg-slate-800/70"
                >
                  <div className="flex items-center gap-4">
                    <div className={`p-3 rounded-xl ${theme.bg}`}>
                      {Icon && <Icon className={`w-6 h-6 ${theme.text}`} />}
                    </div>
                    <div className="text-left">
                      <h3 className="text-xl font-semibold text-slate-200">{category.name}</h3>
                      <p className="text-sm text-slate-400">{category.description}</p>
                    </div>
                  </div>
                  <div className="flex items-center gap-3">
                    {cart.filter(item => item.product.category.startsWith(category.id)).length > 0 && (
                      <span className={`px-3 py-1 rounded-full ${theme.bg} ${theme.text} text-sm font-semibold`}>
                        {cart.filter(item => item.product.category.startsWith(category.id)).reduce((sum, item) => sum + item.quantity, 0)} selected
                      </span>
                    )}
                    {isExpanded ? (
                      <ChevronDown className="w-6 h-6 text-slate-400" />
                    ) : (
                      <ChevronRight className="w-6 h-6 text-slate-400" />
                    )}
                  </div>
                </button>

                {/* Category Content */}
                {isExpanded && (
                  <div className="p-6 border-t border-slate-700 bg-slate-900/50">
                    {renderCategoryContent(category)}
                  </div>
                )}
              </div>
            );
          })}
        </div>
      </div>

      {/* Image Modal */}
      {expandedImage && (
        <div
          className="fixed inset-0 bg-black/90 backdrop-blur-sm z-[60] flex items-center justify-center p-4 animate-in fade-in duration-200"
          onClick={() => setExpandedImage(null)}
        >
          <div
            className="relative max-w-5xl max-h-[90vh] animate-in zoom-in duration-200"
            onClick={(e) => e.stopPropagation()}
          >
            <img
              src={expandedImage.url}
              alt={expandedImage.name}
              className="max-w-full max-h-[90vh] object-contain rounded-2xl shadow-2xl"
            />
            <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 rounded-b-2xl">
              <h3 className="text-white text-xl font-semibold">{expandedImage.name}</h3>
              <p className="text-slate-300 text-sm mt-1">Click anywhere to close</p>
            </div>
            <button
              onClick={() => setExpandedImage(null)}
              className="absolute -top-4 -right-4 p-3 rounded-full bg-white shadow-lg hover:bg-slate-100 transition-colors"
            >
              <X className="w-6 h-6 text-slate-900" />
            </button>
          </div>
        </div>
      )}
    </div>
  );
}