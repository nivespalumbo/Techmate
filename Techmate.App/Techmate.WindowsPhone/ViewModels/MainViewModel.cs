using System;
using System.Collections.ObjectModel;
using System.ComponentModel;
using Techmate.WindowsPhone.Resources;

namespace Techmate.WindowsPhone.ViewModels
{
    public class MainViewModel : INotifyPropertyChanged
    {
        public MainViewModel()
        {
            this.Magazines = new ObservableCollection<MagazineViewModel>();
        }

        /// <summary>
        /// Raccolta per oggetti ItemViewModel.
        /// </summary>
        public ObservableCollection<MagazineViewModel> Magazines { get; private set; }

        public bool IsDataLoaded
        {
            get;
            private set;
        }

        /// <summary>
        /// Crea e aggiunge alcuni oggetti ItemViewModel nella raccolta di elementi.
        /// </summary>
        public void LoadData()
        {
            this.IsDataLoaded = true;
        }

        public event PropertyChangedEventHandler PropertyChanged;
        private void NotifyPropertyChanged(String propertyName)
        {
            PropertyChangedEventHandler handler = PropertyChanged;
            if (null != handler)
            {
                handler(this, new PropertyChangedEventArgs(propertyName));
            }
        }
    }
}