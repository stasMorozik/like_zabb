import { Either, left, right } from "@sweet-monads/either";
import { ajax } from 'rxjs/ajax';
import { catchError, of, Subject, Observable, switchMap } from 'rxjs';
import { AuthorizationUseCase } from '../use-cases/authorization';

export namespace AuthorizationAdapters {
  export class Emiter implements AuthorizationUseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Error, AuthorizationUseCase.Dtos.User>>
    ){}

    emit(either: Either<Error, AuthorizationUseCase.Dtos.User>): void {
      this._subject.next(either)
    }
  }

  export class Api implements AuthorizationUseCase.Ports.Api {
    fetch(): Observable<Either<Error, AuthorizationUseCase.Dtos.User>> {
      return ajax<AuthorizationUseCase.Dtos.User>({
        url: '/api/users/authorize',
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
        },
      }).pipe(
        switchMap((user: AuthorizationUseCase.Dtos.User) => {
          return of(right(user))
        }),
        catchError((error) => {
          //need middleware or interceptor
          if (error.status == 400) {
            return of(left({message: error.response.message} as Error))
          }

          if (error.status == 401) {
            return of(left({message: error.response.message} as Error))
          }

          if (error.status == 404) {
            return of(left({message: 'Not found'} as Error))
          }

          if (error.status == 500) {
            return of(left({message: error.response.message} as Error))
          }

          return of(left({message: 'Something went wrong'} as Error))
        })
      )
    }
  }
}
