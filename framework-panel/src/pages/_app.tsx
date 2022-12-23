import '@styles/globals.scss';
import type { AppProps } from 'next/app'
// Next.js allows you to import CSS directly in .js files.
// It handles optimization and all the necessary Webpack configuration to make this work.
import { config } from '@fortawesome/fontawesome-svg-core'
import { QueryClient, QueryClientProvider } from 'react-query';
import { useSettingsQuery } from '@/data/settings';
import '@fortawesome/fontawesome-svg-core/styles.css'
import type { NextPageWithLayout } from '@/types';
import { SSRProvider } from 'react-bootstrap';
import { Hydrate } from 'react-query/hydration';
import { useRouter } from 'next/router';
import { useState } from 'react';

import ErrorMessage from '@/components/ui/error-message';
import PageLoader from '@/components/ui/page-loader/page-loader';
import { SettingsProvider } from '@/contexts/settings.context';
import { UIProvider } from '@/contexts/ui.context';
import { ModalProvider } from '@/contexts/modal.context';
import PrivateRoute from '@/utils/private-route';


const Noop: React.FC<{ children: React.ReactNode }> = ({ children }) => <>{children}</>;
type AppSettingsType = {
  children: React.ReactNode; // 👈️ type children
};
const AppSettings: React.FC<AppSettingsType> = ({children}) => {
  const { query, locale } = useRouter();
  const { settings, loading, error } = useSettingsQuery({ language: 'EN' });
  if (loading) return <PageLoader />;
  if (error) return <ErrorMessage message={error.message} />;
  // TODO: fix it
  // @ts-ignore
  return <SettingsProvider initialValue={settings?.options} {...children} />;
};

config.autoAddCss = false
type AppPropsWithLayout = AppProps & {
  Component: NextPageWithLayout;
};
function CustomApp({ Component, pageProps }: AppPropsWithLayout) {
  const Layout = (Component as any).Layout || Noop;
  const authProps = (Component as any).authenticate;
  const getLayout = Component.getLayout ?? ((page) => page);
  const [queryClient] = useState(() => new QueryClient());
  return (
    <SSRProvider>
      <QueryClientProvider client={queryClient}>
        <Hydrate state={pageProps.dehydratedState}>
            <AppSettings>
              <UIProvider>
                <ModalProvider>
                  <>
                  <Layout {...pageProps}>
                    <Component {...pageProps} />
                  </Layout>
                  </>
                </ModalProvider>
              </UIProvider>
            </AppSettings>
        </Hydrate>
      </QueryClientProvider>
    </SSRProvider>
  )
}

export default CustomApp
