using Microsoft.Phone.Shell;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Net;
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
           SystemTray.ProgressIndicator = new ProgressIndicator()
            {
                IsIndeterminate = true,
                IsVisible = true
            };

            WebClient webclient = new WebClient();
            webclient.DownloadStringCompleted += (object sender, DownloadStringCompletedEventArgs e) =>
            {
                SystemTray.ProgressIndicator.IsVisible = false;

                if (!string.IsNullOrEmpty(e.Result))
                {
                    Dictionary<string, MagazineViewModel> root = JsonConvert.DeserializeObject<Dictionary<string, MagazineViewModel>>(e.Result);
                    Magazines = new ObservableCollection<MagazineViewModel>(root.Values);
                }

                this.IsDataLoaded = true;
            };

            webclient.DownloadStringAsync(new Uri("http://localhost:8210/Techmate/api/magazine"));
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